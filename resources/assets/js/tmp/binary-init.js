import * as _ from "lodash";


var analyzeTree = function (treeObj) {
    // обходим все Node из списка в поисках Children
    var binaryPositions = ['L', 'R'];


    jQuery(treeObj).each((index, node)=> {

        // Children есть
        if (!node.hasOwnProperty('children')) {
            getVirtualFreeNode(node.child_id, binaryPositions)
            return;
        }

        length = node.children.length;
        if (0 === length) {
            getVirtualFreeNode(node.child_id, binaryPositions)
        }
        ;

        if (1 === length) {
            var existingPostion = node.bt_position;
            var singlePositionArray = existingPostion == 'R' ? ['L'] : ['R'];
            getVirtualFreeNode(node.child_id, singlePositionArray);
            //
            analyzeTree(node.children);
        }

        if (length === 2) {
            // iterate again
            analyzeTree(node.children);
        }
        ;

    });
};


var modifyTree = function (nodes) {

    jQuery(nodes).each((index, node)=> {

        if (node.hasOwnProperty('children')) {
            console.log('Modify node has children: ', node);
            modifyTree(node.children);
        }

        if (!node.hasOwnProperty('child_id')) {
            return;
        }

        var childId = node.child_id;
        if (true == _.includes(_.map(freeNodes, obj=> {
                return obj.searchId
            }), childId)) {
            console.log('We need add to node', childId);


            var newChildrenObj = _.find(freeNodes, obj => {
                return obj.searchId == childId
            });

            if (undefined !== newChildrenObj) {
                var childrenToAdd = newChildrenObj.children;
                // merge with someone
                if (node.hasOwnProperty('children')) {
                    var realChildren = node.children;

                    childrenToAdd = realChildren.concat(childrenToAdd);
                }

                node["children"] = childrenToAdd;


            }

        }

        ;


    });
}

/**
 * Add new objects for as children to parentNodeId
 *
 *
 * @param positions
 * @param parentNodeId
 * @returns {Array}
 */
var createChildrenNodes = function (positions, parentNodeId) {
    var newNodes = [];
    for (var i = 0; i < positions.length; i++) {
        var pos = positions[i];
        var newNode = {
            parent_id: parentNodeId,
            HTMLclass: "free",
            bt_position: pos,
            name: "Free position",
            text: {
                title: "Free position",
                desc: "Binary:" + pos,
            },
            innerHTML: `<div class='binary-data' data-binary-position=${pos} data-parent-id=${parentNodeId}>Free ${pos} position</div>`,
            is_new: true,
        };
        newNodes.push(newNode);
    }
    return newNodes;
};

/**
 * Creates virtual free node and pushes it to freeNodes array
 *
 * @param parentNodeId
 * @param positions
 */
var getVirtualFreeNode = function (parentNodeId, positions) {
    if (parentNodeId === undefined) {
        parentNodeId = window.Btcc.parent.id;
        console.log('Add free root:', parentNodeId, positions);
    }
    console.log('Add free node to:', parentNodeId, positions);

    var newNodes = createChildrenNodes(positions, parentNodeId);

    freeNodes.push({searchId: parentNodeId, children: newNodes});

}


var freeNodes = [];


var initBinaryTree = function (containerID, parentNode, childrenNodes) {

    var parsedChildrenNodes = JSON.parse(childrenNodes);

    console.log(parsedChildrenNodes);

    if (parsedChildrenNodes.length == 0) {
        console.log('Children nodes is null', childrenNodes);
    }

    var treeInstance = (function () {
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
                collapsed: true,
                text: {
                    title: "You",
                    desc: parentNode.name,
                },

                children: parsedChildrenNodes
            }
        };
        return treeConfig;
    })(containerID, parentNode, parsedChildrenNodes);

    // var treeInstance = generataTreantInitialConfig();

    analyzeTree(treeInstance.nodeStructure);

    modifyTree(treeInstance.nodeStructure);

    var treantInstance = new Treant(treeInstance);


    jQuery(containerID).on("click", ".node.free", function (elem) {
        var dataElement = jQuery(this).children(".binary-data").data();

        jQuery("#binary-position").val(dataElement.binaryPosition);
        jQuery("#binary-parent-id").val(dataElement.parentId);
    })
}


export default initBinaryTree;