Chef::Log.info(new_resource.params[:deploy_data])
Chef::Log.info(new_resource)

# install amazon-ssm-agent so we can run commands via EC2 SSM
if !node['vagrant']
    script "install ssm-agent" do
        interpreter "bash"
        user "root"
        code <<-EOH
        yum install -y https://s3.amazonaws.com/ec2-downloads-windows/SSMAgent/latest/linux_amd64/amazon-ssm-agent.rpm
        EOH
    end
end

# small hack to get php-gd to install correctly,
# it depends on a version of libwebp that is newer than amzn-main has
script "install php-gd" do
    interpreter "bash"
    user "root"
    code <<-EOH
    yum -y --disablerepo=amzn-main install libwebp
    yum -y install php-gd
    EOH
end

script "install yum repos for npm and yarn" do
    interpreter "bash"
    user "root"
    cwd "/root"
    code <<-EOH
    curl --silent --location https://rpm.nodesource.com/setup_6.x | bash -
    wget https://dl.yarnpkg.com/rpm/yarn.repo -O /etc/yum.repos.d/yarn.repo
    EOH
end

package "yarn" do
    action :install
end


############## Symfony ####################

# devsite::composer_github_oauth
if node['vagrant']
    deploy_user_home = "/root"
    deploy_username = "root"
    deploy_groupname = "root"
else
    deploy_user_home = "/home/#{node[:deploy][new_resource.params[:app]][:user]}"
    deploy_username = node[:deploy][new_resource.params[:app]][:user]
    deploy_groupname = node[:deploy][new_resource.params[:app]][:group]
end

directory "#{deploy_user_home}/.composer" do
    owner deploy_username
    group deploy_groupname
    mode 00664
    action :create
end

template "#{deploy_user_home}/.composer/config.json" do
    source "#{release_path}/deploy/composer_config.json.erb"
    local true
    mode 0644
end
# end devsite::composer_github_oauth


if !node['vagrant']
    # Set ACL rules to give proper permission to cache and logs
    script "update_symfony_acl" do
      interpreter "bash"
      user "root"
      cwd "#{release_path}/#{node[:symfony][:root]}"
      code <<-EOH
      mkdir -p app/cache app/logs app/cache/#{node[:symfony][:env]}/tmp
      setfacl -R -m u:#{node[:apache][:user]}:rwX -m m:rwX app/cache/ app/logs/ app/cache/#{node[:symfony][:env]}/tmp
      setfacl -dR -m u:#{node[:apache][:user]}:rwX -m m:rwX app/cache/ app/logs/ app/cache/#{node[:symfony][:env]}/tmp
      setfacl -R -m u:#{node[:apache][:user]}:rwX -m m:rwX /srv/www/#{new_resource.params[:app]}/shared/log
      setfacl -dR -m u:#{node[:apache][:user]}:rwX -m m:rwX /srv/www/#{new_resource.params[:app]}/shared/log

      EOH
    end
else
    # Set ACL rules to give proper permission to cache and logs
    script "update_var_lib_php_acl" do
      interpreter "bash"
      user "root"
      cwd "#{release_path}/#{node[:symfony][:root]}"
      code <<-EOH
      setfacl -R -m u:#{node[:apache][:user]}:rwX /var/lib/php/
      setfacl -dR -m u:#{node[:apache][:user]}:rwx /var/lib/php
      EOH
    end
end

case node[:platform]
    when 'debian', 'ubuntu'
      packages = [
      ]

    when 'centos', 'redhat', 'fedora', 'amazon'
      # TODO: Compile php-sqlite extension for RHEL based systems.
      packages = [
        "php-pdo",
        "php-sqlite3"
      ]
end


packages.each do |pkg|
    package pkg do
        action :install
    end
end

package "git" do
    action :install
end

package "sqlite" do
    action :upgrade
end

template "#{release_path}/#{node[:symfony][:root]}/app/config/propel.yml" do
    source "#{release_path}/deploy/templates/propel.yml.erb"
    local true
    mode '0660'
    owner new_resource.params[:deploy_data][:user]
    group new_resource.params[:deploy_data][:group]
    variables(
        :database => new_resource.params[:deploy_data][:database]
    )
end

execute "vendors install" do
    cwd "#{release_path}/#{node[:symfony][:root]}"
    command "composer install --no-interaction"
    action :run
end

execute "propel build-model" do
    command "php app/console propel:model:build"
    cwd "#{release_path}/#{node[:symfony][:root]}"
    action :run
end

execute "propel migrate" do
    command "php app/console --env=#{node[:symfony][:env]} propel:migration:migrate"
    cwd "#{release_path}/#{node[:symfony][:root]}"
    action :run
end


execute "assets install" do
    command "php app/console assets:install --env=#{node[:symfony][:env]} #{release_path}/#{new_resource.params[:deploy_data][:document_root]}"
    cwd "#{release_path}/#{node[:symfony][:root]}"
    action :run
end

execute "assetic dump" do
    command "php app/console assetic:dump --env=#{node[:symfony][:env]} #{release_path}/#{new_resource.params[:deploy_data][:document_root]}"
    cwd "#{release_path}/#{node[:symfony][:root]}"
    action :run
end
###### end symfony2


###### begin angular2
if !node['vagrant']
    script "yarn: install and build" do
        interpreter "bash"
        user "root"
        cwd "#{release_path}/angular"
        code <<-EOH
        yarn install --pure-lockfile
        yarn build
        EOH
    end
end
###### end angular2


# install standard crons on all instances
template "/etc/cron.d/#{new_resource.params[:app]}.standard" do
    source "#{release_path}/deploy/cron.standard.erb"
    local true
    mode '0644'
    owner "root"
    group "root"
    variables(
        :user => node[:apache][:user],
        :env => node[:symfony][:env],
        :symfonyroot => "#{release_path}/#{node[:symfony][:root]}"
    )
end

service "crond" do
    action :restart
end

#
### this is a symfony only app, so don't need to re-write the htaccess file
#
#script "write_htaccess" do
#    interpreter "bash"
#    user "root"
#    cwd "#{release_path}"
#    code <<-EOH
#      echo "RewriteEngine on" > #{new_resource.params[:deploy_data][:document_root]}/.htaccess
#      php app/console --env=#{node[:symfony][:env]} router:dump-apache #{node[:symfony][:frontend]} >> #{release_path}/#{new_resource.params[:deploy_data][:document_root]}/.htaccess
#    EOH
#    # add the line below if using wordpress
#    #       cat .htaccess-wordpress >> #{release_path}/#{new_resource.params[:deploy_data][:document_root]}/.htaccess
#end

template "#{release_path}/#{new_resource.params[:deploy_data][:document_root]}/.htaccess" do
    source "#{release_path}/deploy/templates/symfony-htaccess.erb"
    local true
    mode '0644'
    owner "root"
    group "root"
    variables(
        :user => node[:apache][:user],
        :env => node[:symfony][:env],
        :symfonyroot => "#{release_path}/#{node[:symfony][:root]}",
        :frontend => node[:symfony][:frontend]
    )
end
