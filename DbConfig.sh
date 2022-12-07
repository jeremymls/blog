# HOST
clear
echo -e '\e[1;34mDB_HOST? [localhost]\e[0m\n'
printf '\e[1;33m> '
read DB_HOST
if [ -z "$DB_HOST" ]; then
    DB_HOST="localhost"
fi
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/g" .env.exemple

# DB_PORT
clear
echo -e '\e[1;34mDB_PORT? [3306]\n'
printf '\e[1;33m> '
read DB_PORT
if [ -z "$DB_PORT" ]; then
    DB_PORT="3306"
fi
sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/g" .env.exemple

# DB_NAME
clear
echo -e '\e[1;34mDB_NAME? [blog]\n'
printf '\e[1;33m> '
read DB_NAME
if [ -z "$DB_NAME" ]; then
    DB_NAME="blog"
fi
sed -i "s/DB_NAME=.*/DB_NAME=${DB_NAME}/g" .env.exemple

# DB_USER
clear
echo -e '\e[1;34mDB_USER? [root]\n'
printf '\e[1;33m> '
read DB_USER
if [ -z "$DB_USER" ]; then
    DB_USER="root"
fi
sed -i "s/DB_USER=.*/DB_USER=${DB_USER}/g" .env.exemple

# DB_PASS
clear
echo -e '\e[1;34mDB_PASS? [password]\n'
printf '\e[1;33m> '
read -s DB_PASS
if [ -z "$DB_PASS" ]; then
    DB_PASS="password"
fi
sed -i "s/DB_PASS=.*/DB_PASS=${DB_PASS}/g" .env.exemple

clear
echo -e '\e[5;32mConfiguration terminée.\e[0m\n'