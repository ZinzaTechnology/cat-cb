# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "CentOS6.5-mng-20160105"
  config.vm.box_url = "http://radicode.co.jp/centos6.5-mng-20160105.box"
  config.vm.network :forwarded_port, guest: 80, host: 8000
  config.vm.network :forwarded_port, guest: 10000, host: 10001
  config.vm.network :private_network, ip: "192.168.33.33"
  config.vm.synced_folder "", "/virtual/vagrant/public_html/www.zzzzzzzz.jp", mount_options: ['dmode=777','fmode=777']
  config.vm.provision :shell, path: "./provision-script/fuelphp.sh", privileged: true
end
