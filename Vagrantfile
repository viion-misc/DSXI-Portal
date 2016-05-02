# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
    # The box
    config.vm.box = "ubuntu/trusty64"
    config.vm.provider :virtualbox do |vb|
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.memory = 4086
        vb.cpus = 2
    end

    # config
    config.vm.network "private_network", ip: "11.11.11.11"
    config.vm.network "forwarded_port", guest: 80, host: 1111
    config.vm.hostname = "dsxi.server"
    config.vm.synced_folder ".", "/dsxi", type: "nfs"

    # host manager
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
end

