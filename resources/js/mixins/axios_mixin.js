export default {
    methods: {
        get_articles(category, lang, onSuccess, onError, onFinally){
            axios
                .get('/get_article/get_locale_articles/' + category + '/' + lang)
                .then(response => {
                    if (onSuccess) onSuccess(response.data);
                })
                .catch(error => {
                    if (onError) onError(error.response ? error.response.data : error);
                })
                .finally(() => {
                    if (onFinally) onFinally();
                });
        },
    }
};
