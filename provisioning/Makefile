installs:
	ssh user@ip 'bash -s' < installs.sh

site:
	ansible-playbook -i hosts.yml site.yml

renew-certificates:
	ansible-playbook -i hosts.yml renew-certificates.yml

authorize:
	ansible-playbook -i hosts.yml authorize.yml