<template>
    <div class="navbar-brand currency-informer">
       <p>BTC: <strong>{{ currency.BTC }} $</strong></p>
    </div>
</template>


<script>
  //  var Vue = require('vue');
export default {
    data () {
        return {
            currency : { BTC: '' }
        };
    },
    created: function () {
        this.fetchData();

    },
    methods: {
        fetchData: function () {
            var self = this;

            this.$http.get("https://api.coinapult.com/api/ticker", {

            }).then((response) => {
               // console.log(response);
                self.currency.BTC =response.data.index;
                  setTimeout(self.fetchData,10000);
            }, (response)=> {
                //console.log('Error',response);

            });
        }
    }

};
</script>






































