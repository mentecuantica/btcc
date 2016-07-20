var Vue = require('vue');
var VueResource= require('vue-resource');
Vue.use(VueResource);

import initTernaryTree from './trees/ternary-init.js';
import BinaryTreeOutput from './trees/binary-tree';
import CurrencyInformer from './components/CurrencyInformer.vue';

window.Btcc = window.Btcc || {};

if (Btcc == {}) {

}
else {
    if (Btcc.tree == 'binary') {
        let b = new BinaryTreeOutput(Btcc.childrenNodes,Btcc.parent);
    }
    if (Btcc.tree=='ternary') {
        initTernaryTree("#tree-container",Btcc.parent,Btcc.childrenNodes);
    }
}

new Vue({
    el: "#app-layout",
    components: {
        currencyinformer: CurrencyInformer
    }
});
/*
new Vue({
    el: "#life-widgets",
    data: window.Btcc,
    

});*/
