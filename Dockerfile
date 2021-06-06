# Inherit Ubuntu Image
FROM ubuntu:20.04

ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Kolkata

RUN apt-get update
# Install Mojolicious
RUN apt install curl -y
RUN apt install make -y
RUN apt install vim -y

# Install Git
RUN apt install git -y

# Install MariaDB Server
RUN apt-get install -y mariadb-server

# add a user - this user will own the files in /home/ash
RUN useradd --user-group --create-home --shell /bin/false ash

# set up and copy files to /home/app
ENV HOME=/usr/ash
WORKDIR /home/ash
COPY . /home/ash/

EXPOSE 8000/tcp
EXPOSE 80/tcp
EXPOSE 8080/tcp

