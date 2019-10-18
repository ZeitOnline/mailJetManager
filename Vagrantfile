# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT

      echo "I am provisioning ubuntu 16 for SAM2 downgrading php to 5.6 and install all components"
      date > /etc/vagrant_provisioned_at

      apt update && apt upgrade
      apt-get install -y unzip
      apt-get install -y language-pack-de
      apt-get install -y locate
      apt-get install -y php-curl
      apt-get install -y php php-pgsql
      apt-get install -y postgresql libpq5 postgresql-9.5 postgresql-client-9.5 postgresql-client-common postgresql-contrib phppgadmin

      echo "sudo -i -u postgres
      $ psql# CREATE USER root WITH PASSWORD 'root';
      # CREATE DATABASE "test";
      # GRANT ALL ON DATABASE "test" TO root;
      # \q$ exit"



SCRIPT


# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.define "mailjet-contacts", primary: true do |myVM|
    myVM.vm.box = "ubuntu/xenial64"
    myVM.ssh.forward_agent = true
    myVM.vm.synced_folder ".", "/vagrant", type: "nfs"
    myVM.vm.provision "shell", inline: $script
#   myVM.vm.provision "chef_solo" do |chef|
#      chef.version = "12.21.31"
#      chef.install = true
#      chef.log_level = 'info'
#      chef.nodes_path = "../chef/nodes"
#      chef.cookbooks_path = "../chef/cookbooks"
#      chef.environments_path = "../chef/environments"
#      chef.environment = "zeit-production"
#      chef.environment = "zeit-staging"
#      chef.data_bags_path = "../chef/data_bags"
#      chef.roles_path = "../chef/roles"
#      chef.add_role "academics-recruiterDB"
#     chef.encrypted_data_bag_secret_key_path = "encrypted_data_bag_secret"
#  end
    myVM.vm.network :private_network, ip: "192.168.34.66"
#    myVM.vm.network :forwarded_port, guest: 3306, host: 3306
    myVM.vm.network :forwarded_port, guest: 80, host: 80
#    myVM.vm.network :forwarded_port, guest: 1337, host: 1337
#    myVM.vm.network :forwarded_port, guest: 21, host: 21
    myVM.vm.provider "virtualbox" do |v|
      v.memory = 4000 
      v.customize ["modifyvm", :id, "--cpus", 2]
    end
  end
end

