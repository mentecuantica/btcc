import * as _ from "lodash";


function decorateTernaryTreeConfig(usersNestedArray) {
    usersNestedArray = _.sortBy(usersNestedArray,'t_position');



    _.forEach(usersNestedArray, (node, key)=> {

        if (node.name==null) {
            node.name = `ID ${node.user_id}`;
        }
        node["text"]={
            title:  node.t_position,
            name:   node.name,
            desc: node.name,
        };
        if (node.hasOwnProperty('children')) {
            decorateTernaryTreeConfig(node.children);
        };
        return node;
    });

};
function initTernaryTree(containerID, parentNode, childrenNodes) {

    childrenNodes = _.sortBy(childrenNodes,'t_position');


    //var chi1 =
    decorateTernaryTreeConfig(childrenNodes);

    //console.log(chi1);


    var treeInstance = (()=> {
        "use strict";
        var treeConfig = {
            chart: {
                container: containerID,
                node: {
                    collapsable: true
                },
            },
            nodeStructure: {
                HTMLclass: "owner",
                child_id: parentNode.child_id,
                collapsed: false,
                text: {
                    title: "You",
                    desc: parentNode.name,
                },

                children: childrenNodes
            }
        };
        return treeConfig;
    })(containerID, parentNode, childrenNodes);


    var treantInstance = new Treant(treeInstance);


};



export default initTernaryTree;
