@echo off
echo 修复Git推送问题...
cd /d F:\01-个人网站\linked-pages

echo 检查Git安装...
git --version
if %errorlevel% neq 0 (
    echo 请安装Git: https://git-scm.com/download/win
    pause
    exit /b
)

echo 清理旧的Git配置...
if exist ".git" rmdir /s /q ".git"
git init

echo 配置用户信息...
git config user.name "Chopiglat"
git config user.email "你的邮箱@github.com"

echo 配置远程仓库...
git remote remove origin 2>nul
git remote add origin https://github.com/Chopiglat/Chopiglat.github.io.git

echo 添加文件...
git add .
git commit -m "网站更新 %date%"

echo 强制推送...
git branch -M main
git push -u origin main --force

echo 完成！访问 https://chopiglat.github.io/
pause