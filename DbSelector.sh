#fonction menu
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

LINE=`grep -n DB_NAME .env | cut -d ':' -f1`
DB_NAME=`sed -n $LINE'p' .env | cut -d '=' -f2`

clear
echo -e "\e[1;36mDB_NAME: \e[1;32m$DB_NAME\e[0m"

db=(
    "blog"
    "blog_test"
    "Autre"
    "Quitter"
)

choose_from_menu "\n\e[1;37mChoisissez la base de données à utiliser\e[0m\n" db_name "${db[@]}"

function set_db_name() {
    sed -i "s/DB_NAME=.*/DB_NAME=${db_name}/g" .env
    clear
    echo -e "\e[1;36mVous avez choisi la base de données \e[1;32m${db_name}\e[0m\n"
}

case ${db_name} in
    "blog")
        set_db_name;;
    "blog_test")
        set_db_name;;
    "Autre")
        echo -e "\n\e[1;37mEntrez le nom de la base de données:\e[0m\n"
        printf '\e[1;33m> '
        read db_name
        if [ -z ${db_name} ]; then
            clear
            echo -e "\n\e[1;31mVous devez entrer un \e[1;33mnom\e[1;31m de base de données!\e[0m\n"
            exit 1
        else
            set_db_name
        fi;;
    "Quitter" | *)
        clear
        echo -e "\e[1;31mVous avez quitté\e[0m\n"
        exit 0
        ;;
esac
