package "mysql" do
  source "https://dev.mysql.com/get/mysql57-community-release-el7-11.noarch.rpm"
  action :install
  provider Chef::Provider::Package::Rpm
end

package "mysql-community-server" do
  action :install
end


# mysql_service 'default' do 
#   version '5.7'
#   package_name 'mysql-community-server'
#   bind_address '0.0.0.0'
#   port '3306'
#   data_dir '/data'
#   initial_root_password node['mysql']['server_root_password']
#   action [:create, :start]
# end

