# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "dayspring-tech/dayspring-centos6-php72-js"

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 3306, host: 8306

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  
  config.vm.provider "virtualbox" do |vb|
    # Display the VirtualBox GUI when booting the machine
    # vb.gui = true
  
    # Customize the amount of memory on the VM:
    vb.memory = "4096"
  end
  
  # View the documentation for the provider you are using for more
  # information on available options.

  # Define a Vagrant Push strategy for pushing to Atlas. Other push strategies
  # such as FTP and Heroku are also available. See the documentation at
  # https://docs.vagrantup.com/v2/push/atlas.html for more information.
  # config.push.define "atlas" do |push|
  #   push.app = "YOUR_ATLAS_USERNAME/YOUR_APPLICATION_NAME"
  # end

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  # config.vm.provision "shell", inline: <<-SHELL
  #   sudo apt-get update
  #   sudo apt-get install -y apache2
  # SHELL

  # load external file that holds your github oauth token
  composer_github_oauth = ""
  custom_vagrantfile = 'Vagrantfile.local'
  if File.exist?(custom_vagrantfile)
    external = File.read custom_vagrantfile
    eval external
  end

  application_name = "symfony"
  document_root = "symfony/web"
  database_info = {
    "username" => "devuser",
    "password" => "devpass",
    "host" => "localhost",
    "database" => "mydatabase"
  }

  config.vm.provision :chef_solo do |chef|

    # Site specific configuration here
    chef.json = {
      "symfony" => {
        "root" => "symfony",
        "env" => "dev",
        "frontend" => "app_dev.php",
        "use_composer" => true,
        "propelwithmigration" => true
      },
      "composer" => {
        'install_globally' => true,
        'github_oauth' => composer_github_oauth
      },
      "php_ini" => {
        "date.timezone" => "UTC"
      },

      # stuff below here is just vagrant stuff, no need to modify
      # some of this is to provide what opsworks would have provided

      "vagrant" => true,
      "deploy" => {
        application_name => {
          "name" => application_name,
          "application" => application_name,
          "application_type" => "php",
          "deploy_to" => "/vagrant",
          "document_root" => document_root,
          "domains" => [],
          "ssl_support" => false,
          "database" => database_info,
          "user" => "vagrant",
          "group" => "vagrant",
          "primary_instance_hostname" => "primary"
        }
      },
      "apache" => {
        "user" => "vagrant",
        "mod_php" => {
         "module_name" => "php7",
         "so_filename" => "libphp7.so"
        }
      },
      "mysql" => {
        "server_root_password" => "rootpass",
        "server_repl_password" => "rootpass",
        "server_debian_password" => "rootpass",
        "allow_remote_root" => true,
        "remove_anonymous_users" => true,
        "remove_test_database" => true
      },
      "opsworks" => {
        "deployment" => "HEAD",
        "instance" => {
          "hostname" => "primary"
        }
      }
    }

    chef.version = "12.10"
    # set chef channel to stable on vagrant > 1.7.4
    if Gem::Version.new(Vagrant::VERSION) > Gem::Version.new('1.7.4')
      chef.channel = 'stable'
    end
    chef.cookbooks_path = ["./vagrant/cookbooks"]
    chef.roles_path = "./vagrant/roles"

    chef.add_role "webapp"
  end

end
