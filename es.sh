#!/bin/bash

# 定义颜色
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# 检查 wget 是否存在
if ! command -v wget >/dev/null 2>&1; then
    printf "${YELLOW}wget 未找到，正在安装...${NC}\n"
    sudo apt-get update
    sudo apt-get install -y wget
fi

# 定义下载链接
JDK_DOWNLOAD_URL="https://repo.huaweicloud.com/openjdk/18.0.2.1/openjdk-18.0.2.1_linux-x64_bin.tar.gz"
ES_DOWNLOAD_URL="https://repo.huaweicloud.com/elasticsearch/7.9.3/elasticsearch-7.9.3-linux-x86_64.tar.gz"

# 创建目录
printf "${GREEN}创建目录...${NC}\n"
mkdir -p ~/elk

# 下载 JDK
printf "${GREEN}下载 JDK...${NC}\n"
wget -q -P ~/elk $JDK_DOWNLOAD_URL

# 解压 JDK
printf "${GREEN}解压 JDK...${NC}\n"
tar -zxf ~/elk/openjdk-18.0.2.1_linux-x64_bin.tar.gz -C ~/elk

# 配置环境变量
printf "${GREEN}配置 JDK 环境变量...${NC}\n"
echo "export JAVA_HOME=~/elk/jdk-18.0.2.1" >> ~/.bashrc
echo "export PATH=\$JAVA_HOME/bin:\$PATH" >> ~/.bashrc
source ~/.bashrc

# 下载 Elasticsearch
printf "${GREEN}下载 Elasticsearch...${NC}\n"
wget -q -P ~/elk $ES_DOWNLOAD_URL

# 解压 Elasticsearch
printf "${GREEN}解压 Elasticsearch...${NC}\n"
tar -zxf ~/elk/elasticsearch-7.9.3-linux-x86_64.tar.gz -C ~/elk

# 更改权限
printf "${GREEN}更改 Elasticsearch 权限...${NC}\n"
chmod 755 ~/elk/elasticsearch-7.9.3/bin/elasticsearch

# 完成安装
printf "${GREEN}Elasticsearch 和 JDK 安装完成！${NC}\n"

