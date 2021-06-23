############################### INSTALL DOCKER ###############################

echo -e "\n\n"
echo "----------------------- INSTALL DOCKER --------------------"
sudo apt-get update
sudo apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo \
  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io
sudo docker run hello-world
sudo groupadd docker
sudo usermod -aG docker $USER
docker --version
sudo systemctl enable docker.service
sudo systemctl enable containerd.service
#docker run hello-world after reconnect to ssh server

############################### END DOCKER ###############################



############################### INSTALL DOCKER-COMPOSE ###############################

echo -e "\n\n"
echo "----------------------- INSTALL DOCKER-COMPOSE --------------------"
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version

############################### END DOCKER-COMPOSE ###############################



############################### CREATE CRON ###############################

echo -e "\n\n"
echo "----------------------- CREATE CRON --------------------"
crontab -l > mycron
echo "0 1 * * * 'docker system prune -af --filter \"until=$((30*24))h\"'" >> mycron
#install new cron file
crontab mycron
rm mycron

echo -e "cron list: " && crontab -l

############################### CREATE CRON ###############################