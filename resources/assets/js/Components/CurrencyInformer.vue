<template>
    <div>
        <h3>BTC ticker</h3>
        <p>{{ currency.BTC  }} $</p>
    </div>
</template>


<script>
  //  var Vue = require('vue');
export default {
    data () {
        return {
            currency : { BTC: 2048.1 }
        };
    },
    created: function () {
        this.fetchData();

    },
    methods: {
        fetchData: function () {
            var self = this;
            //self.currency.BTC = 6969.69;

            this.$http.get("https://api.coinapult.com/api/ticker", {

            }).then((response) => {
                console.log(response);
                self.currency.BTC =response.data.index;
                  setTimeout(self.fetchData,1000);
            }, (response)=> {
                console.log('Error',response);

            });
        }
    }

};
</script>






































