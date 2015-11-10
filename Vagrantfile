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
  config.vm.box = "ubuntu/trusty64"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  config.vm.network :forwarded_port, guest: 80, host: 8888, auto_correct: true

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  config.vm.synced_folder "./", "/var/www", create: true, group: "www-data", owner: "www-data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider "virtualbox" do |v|
      v.name = "SitePoint Test Vagrant"
      v.customize ["modifyvm", :id, "--memory", "1024"]
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
  config.vm.provision "shell", inline: <<-SHELL
    sed -i 's/^mesg n$/tty -s \&\& mesg n/g' /root/.profile
  
    export DEBIAN_FRONTEND=noninteractive
    export LANGUAGE=en_US.UTF-8
    export LANG=en_US.UTF-8
    export LC_ALL=en_US.UTF-8
    locale-gen en_US.UTF-8
    dpkg-reconfigure locales
  
    
    echo "Running Updates"
    sudo apt-get update > /dev/null 2>&1
    
    echo "Installing Git"
    sudo apt-get install git -y > /dev/null 2>&1
    
    echo "Updating PHP repository"
    sudo add-apt-repository ppa:ondrej/php5-5.6 -y > /dev/null 2>&1
    sudo apt-get update > /dev/null 2>&1
    sudo apt-get install python-software-properties -y > /dev/null 2>&1
    sudo apt-get update > /dev/null 2>&1

    echo "Installing PHP"
    sudo apt-get install php5 -y > /dev/null 2>&1

    echo "Installing PHP extensions"
    sudo apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql -y > /dev/null 2>&1
    
    echo "Enabling Apache Modules"
    sudo a2enmod rewrite > /dev/null 2>&1
    
    echo "Installing MySQL"
    sudo apt-get install debconf-utils -y > /dev/null 2>&1

    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password needmorecoffee" > /dev/null 2>&1
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password needmorecoffee" > /dev/null 2>&1
   
    sudo apt-get install mysql-server -y > /dev/null 2>&1
    
    echo "Enabling Site"
    sudo rm -rf /etc/apache2/sites-enabled/* > /dev/null 2>&1
    sudo cp /var/www/lis.conf /etc/apache2/sites-available/lis.conf > /dev/null 2>&1
    
    sudo a2ensite lis > /dev/null 2>&1
    sudo service apache2 restart > /dev/null 2>&1
    echo "Finished"
  SHELL
end
