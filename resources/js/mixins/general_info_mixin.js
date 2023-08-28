export const general_info = {
    get_value_insert_text({locale, form_data, form_value_name}) {
        this.data[locale+"_data"][form_value_name] = form_data
    },
    get_global_blocks_status_action({value_name, block_action}) {
        this.global_blocks[value_name] = block_action
    },
    get_global_blocks_id({value_name, block_id}) {
        this.global_blocks[value_name+"_id"] = block_id
    },
}