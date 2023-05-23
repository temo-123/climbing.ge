export const going = {
    back(then, back_action = false){
        if(back_action == false){
            if(confirm('Are you sure, you want go back?')){
                then.$router.go(-1)
            }
        }
        else{
            then.$router.go(-1)
        }

        return false
    },
}