installs:
	ssh user@ip 'bash -s' < installs.sh

site:
	ansible-playbook -i hosts.yml site.yml # -vvvv show log level details

upgrade:
	ansible-playbook -i hosts.yml upgrade.yml

renew-certificates:
	ansible-playbook -i hosts.yml renew-certificates.yml

authorize:
	ansible-playbook -i hosts.yml authorize.yml

docker-login:
	ansible-playbook -i hosts.yml docker-login.yml

check-alive-hosts:
	ansible all -m ping -i hosts.yml