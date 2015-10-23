#!/usr/bin/env bash

# Enable EPEL repo's
sudo yum install -y epel-release

# Install ansible
sudo yum install -y ansible

# Setup ansible for local use and run the playbook
cp /vagrant/ansible/inventories/development /etc/ansible/hosts -f
sudo chmod 666 /etc/ansible/hosts
sudo ansible-playbook /vagrant/ansible/development.yml -e hostname=192.168.67.10 --connection=local
