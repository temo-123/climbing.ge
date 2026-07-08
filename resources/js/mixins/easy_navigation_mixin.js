export const going = {
    back(then, back_action = false){
        if(back_action == false){
            if(confirm(then.$t('global.confirm_go_back'))){
                then.$router.go(-1)
            }
        }
        else{
            then.$router.go(-1)
        }

        return false
    },
}