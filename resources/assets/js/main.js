var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);

import CurrencyInformer from './components/CurrencyInformer.vue';
import BinaryTreeOutput from './trees/binary-tree';
import initTernaryTree  from './trees/ternary-init.js';

new Vue({
    el: "#app-layout",
    components: {
        currencyinformer: CurrencyInformer
    }
});


window.Btcc = window.Btcc || {tree: 'nullable'};

/*if (Btcc == {}) {

 }
 else {*/
if (Btcc.tree == 'binary') {
    var b = new BinaryTreeOutput(Btcc.childrenNodes, Btcc.parent);
}
if (Btcc.tree == 'ternary') {
    initTernaryTree("#tree-container", Btcc.parent, Btcc.childrenNodes);

}
