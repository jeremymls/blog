#!/bin/bash

#Couleurs
RED='\033[1;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# Fonctions
choice=(
    "Oui"
    "Non"
)

function choose_from_menu() {
    local prompt="$1" outvar="$2"
    shift
    shift
    local options=("$@") cur=0 count=${#options[@]} index=0
    local esc=$(echo -en "\e") # cache ESC as test doesn't allow esc codes
    printf "${PURPLE}$prompt\n${UNSET}"
    while true
    do
        # list all options (option list is zero-based)
        index=0 
        for o in "${options[@]}"
        do
            if [ "$index" == "$cur" ]
            then echo -e " >\e[7m$o\e[0m" # mark & highlight the current option
            else echo "  $o"
            fi
            index=$(( $index + 1 ))
        done
        read -s -n3 key # wait for user to key in arrows or ENTER
        if [[ $key == $esc[A ]] # up arrow
        then cur=$(( $cur - 1 ))
            [ "$cur" -lt 0 ] && cur=0
        elif [[ $key == $esc[B ]] # down arrow
        then cur=$(( $cur + 1 ))
            [ "$cur" -ge $count ] && cur=$(( $count - 1 ))
        elif [[ $key == "" ]] # nothing, i.e the read delimiter - ENTER
        then break
        fi
        echo -en "\e[${count}A" # go up to the beginning to re-render
    done
    # export the selection to the requested output variable
    printf -v $outvar "${options[$cur]}"
}

function config_DB(){
    local env="$1" # DEV, TEST, PROD

    echo -e "${BLUE}DB_HOST? [localhost]${NC}"
    printf "${YELLOW}> "
    read DB_HOST
    if [ -z "$DB_HOST" ]; then
        DB_HOST="localhost"
    fi
    echo -e "${env}_DB_HOST=${DB_HOST}" >> .env

    echo -e "${BLUE}DB_PORT? [3306]"
    printf "${YELLOW}> "
    read DB_PORT
    if [ -z "$DB_PORT" ]; then
        DB_PORT="3306"
    fi
    echo -e "${env}_DB_PORT=${DB_PORT}" >> .env

    if [ "$env" == "PROD" ]; then
        echo -e "${BLUE}DB_NAME? [blog]"
        echo -e "${WHITE}Attention, cette base de données doit exister!${NC}"
        printf "${YELLOW}> "
        read DB_NAME
        if [ -z "$DB_NAME" ]; then
            DB_NAME="blog"
        fi
    elif [ "$env" == "DEV" ]; then
        echo -e "${BLUE}DB_NAME? [blog_dev]"
        echo -e "${WHITE}Attention, cette base de données doit exister!${NC}"
        printf "${YELLOW}> "
        read DB_NAME
        if [ -z "$DB_NAME" ]; then
            DB_NAME="blog_dev"
        fi
    elif [ "$env" == "TEST" ]; then
        echo -e "${BLUE}DB_NAME? [blog_test]"
        echo -e "${WHITE}Attention, cette base de données doit exister!${NC}"
        printf "${YELLOW}> "
        read DB_NAME
        if [ -z "$DB_NAME" ]; then
            DB_NAME="blog_test"
        fi
    fi
    echo -e "${env}_DB_NAME=${DB_NAME}" >> .env

    echo -e "${BLUE}DB_USER? [root]"
    printf "${YELLOW}> "
    read DB_USER
    if [ -z "$DB_USER" ]; then
        DB_USER="root"
    fi
    echo -e "${env}_DB_USER=${DB_USER}" >> .env

    echo -e "${BLUE}DB_PASS? [password]"
    printf "${YELLOW}> "
    read -s DB_PASS
    if [ -z "$DB_PASS" ]; then
        DB_PASS="password"
    fi
    echo -e "${env}_DB_PASS=${DB_PASS}" >> .env
}

function empty_DB() {
    local env="$1" # DEV, TEST, PROD
    echo -e "${env}_DB_HOST=" >> .env
    echo -e "${env}_DB_PORT=" >> .env
    echo -e "${env}_DB_NAME=" >> .env
    echo -e "${env}_DB_USER=" >> .env
    echo -e "${env}_DB_PASS=" >> .env
}

function break_point() {
    echo -e "${WHITE}\nAppuyez sur une touche pour continuer...${NC}"
    read -n 1
    # printf "."
    # sleep 1
    # printf "."
    # sleep 1
    # printf "."
    # sleep 1
}


# Vérification de la présence de .env
if [ -f .env ]; then
    # Environnement du fichier .env
    ENV=$(grep APP_ENV= .env | cut -d '=' -f2)
    clear
    echo -e "${BLUE}Environnement actuel: ${RED}${ENV}${NC}\n"
    echo -e "${YELLOW}Un fichier .env existe déjà.\nIl sera écrasé.${NC}\n"
    choose_from_menu "${BLUE}Voulez-vous le configurer à nouveau?${NC}\n" RECONFIGURE "${choice[@]}"
fi
if [ -z "$RECONFIGURE" ]; then
    RECONFIGURE="Oui"
fi
if [ "$RECONFIGURE" == "Oui" ]; then
    clear
    if [ -z "$ENV" ]; then
        db=(
            "DEV"
            "PROD"
        )
        choose_from_menu "${BLUE}Choisissez un environnement${NC}\n" ENV "${db[@]}"
        # ENV="DEV"
    fi
    echo -e "${BLUE}Environnement: ${ENV}${NC}"


    echo -e "APP_ENV=${ENV}" > .env
    echo -e "" >> .env

    # Site URL
    echo -e "## SITE_URL ##" >> .env
    clear
    echo -e "${BLUE}SITE_URL? [http://localhost:8000]${NC}\n"
    printf "${YELLOW}> "
    read SITE_URL
    if [ -z "$SITE_URL" ]; then
        SITE_URL="http://localhost:8000"
    fi
    echo -e "SITE_URL=${SITE_URL}" >> .env
    echo -e "" >> .env

    # Mysql
    echo -e "###> Mysql configuration ###" >> .env
    echo -e "### $ENV ###" >> .env
    clear
    echo -e "${BLUE}Configuration de la base de données de l'environnement ${ENV}${NC}\n"

    config_DB "$ENV"

    clear
    echo -e "\n### TEST ###" >> .env
    choose_from_menu "${BLUE}Configurer un environnement de test ?${NC}\n" TEST_ENV "${choice[@]}"
    if [ "${TEST_ENV}" = "Oui" ]; then
        clear
        echo -e "${BLUE}Configuration de la base de données de l'environnement de TEST${NC}\n"
        config_DB "TEST"
    else
        empty_DB "TEST"
    fi

    if [ "${ENV}" = "DEV" ]; then
        clear
        echo -e "\n### PROD ###" >> .env
        choose_from_menu "${BLUE}Configurer un environnement de production ?${NC}\n" PROD_ENV "${choice[@]}"
        if [ "${PROD_ENV}" = "Oui" ]; then
            clear
            echo -e "${BLUE}Configuration de la base de données de l'environnement de PROD${NC}\n"
            config_DB "PROD"
        else
            empty_DB "PROD"
        fi
    else
        echo -e "\n### DEV ###" >> .env
        empty_DB "DEV"
    fi
    clear
    echo -e "###< Mysql configuration ###\n" >> .env

    echo -e "### ASSETS CONFIGURATION ###" >> .env
    choose_from_menu "${BLUE}Configurer un dossier d'assets ?${NC}\n" ASSETS "${choice[@]}"
    if [ "${ASSETS}" = "Oui" ]; then
        echo -e "${BLUE}ASSETS_PATH? [assets]${NC}"
        printf "${YELLOW}> "
        read ASSETS_PATH
        if [ -z "$ASSETS_PATH" ]; then
            ASSETS_PATH="assets"
        fi
    else
        ASSETS_PATH="assets"
    fi
    echo -e "ASSETS=${ASSETS_PATH}" >> .env

    echo -e "\n###> don't modify the following line ###" >> .env
    echo -e "ASSETS_PATH=\${SITE_URL}/\${ASSETS}/" >> .env
    echo -e "XDEBUG_MODE=coverage" >> .env
    echo -e "###< don't modify the previous line ###" >> .env
fi
# Migration
clear
choose_from_menu "${BLUE}Migrer la base de données?${NC}\n" MIGRATE "${choice[@]}"
if [ "${MIGRATE}" = "Oui" ]; then
    echo -e "${YELLOW}Migration de la base de données...${NC}"
    vendor/bin/phinx migrate -e $ENV
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Migration effectuée.${NC}"
    else
        echo -e "${RED}Erreur lors de la migration.${NC}"
    fi
    break_point
fi

# CSS variables colors
echo -e ":root{
    --theme-primary: #81260a;
    --theme-primary-darken: #5c1d08;
    --theme-success: #dd9b0d;
    --theme-success-darken: #b37d0a;
    --theme-success-fade: #dd9b0dee;
}" > assets/css/styles.css


# Vérification de la présence de composer
if ! [ -x "$(command -v composer)" ]; then
echo -e "${RED}Composer n'est pas installé.${NC}"
echo -e "${YELLOW}Installation de composer...${NC}"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
echo -e "${GREEN}Composer installé.${NC}"
fi
# if [ -f composer.lock ]; then
#     rm composer.lock
# fi
# if [ -d vendor ]; then
#     rm -rf vendor
# fi
# if [ -d node_modules ]; then
#     rm -rf node_modules
# fi
# if [ -f package-lock.json ]; then
#     rm package-lock.json
# fi
# if [ -f yarn.lock ]; then
#     rm yarn.lock
# fi
clear
choose_from_menu "${BLUE}Installer les dépendances ?${NC}\n" INSTALL_DEPS "${choice[@]}"
if [ "${INSTALL_DEPS}" = "Oui" ]; then
    echo -e "${YELLOW}Installation des dépendances...${NC}"
    if [ $ENV == "DEV" ]; then
        composer install
    else
        composer install --no-dev
    fi
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Dépendances installées.${NC}"
    else
        echo -e "${RED}Erreur lors de l'installation des dépendances.${NC}"
    fi
    break_point
fi
# echo -e "${YELLOW}Installation des dépendances front...${NC}"
# yarn install --production
# echo -e "${GREEN}Dépendances front installées.${NC}"
# echo -e "${YELLOW}Compilation des assets...${NC}"
# yarn encore production
# echo -e "${GREEN}Assets compilés.${NC}"

clear
SITE_URL=$(grep SITE_URL= .env | cut -d '=' -f2)
echo -e "${GREEN}Configuration terminée.\n${BLUE}Rendez-vous sur ${WHITE}${SITE_URL}${BLUE} pour personnaliser votre site.${NC}\n"
exit 0