var analyzeTree = function (treeObj) {
    // обходим все Node из списка в поисках Children
    var binaryPositions = ['L', 'R'];

    jQuery(treeObj).each(function (index, node) {

        // Children есть
        if (node.hasOwnProperty('children')) {
            console.log('Index', index, 'treeObj', node);
            length = node.children.length;
            if (0 === length) {
                getVirtualFreeNode(node.child_id, binaryPositions)
                // getVirtualFreeNode(node.child_id, binaryPositions);
            }
            ;

            if (1 === length) {
                var existingPostion = node.bt_position;
                var singlePositionArray = existingPostion == 'R' ? ['L'] : ['R'];

                console.log({'Single': singlePositionArray});
                getVirtualFreeNode(node.child_id, singlePositionArray);
                //
                analyzeTree(node.children);
            }

            if (length === 2) {
                // iterate again
                analyzeTree(node.children);
            }
            ;
        }
        else {
            // Children нет
            getVirtualFreeNode(node.child_id, binaryPositions)

        }
    });
};


var modifyTree = function (nodes) {

    jQuery(nodes).each(function (index, node) {

        if (node.hasOwnProperty('children')) {
            //console.log('Recuresion for children: ',index);
            modifyTree(node.children);
        }

        if (node.hasOwnProperty('child_id')) {

            var childId = node.child_id;
            if (true == _.contains(_.map(freeNodes, function (obj) {
                    return obj.searchId;
                }), childId)) {
                console.log('We need add to node', childId);


                var newChildrenObj = _.find(freeNodes, function (obj) {
                    return obj.searchId == childId;
                });

                if (undefined !== newChildrenObj) {
                    var childrenToAdd = newChildrenObj.children;
                    // merge with someone
                    if (node.hasOwnProperty('children')) {
                        var realChildren = node.children;

                        childrenToAdd = realChildren.concat(childrenToAdd);
                        node["children"] = childrenToAdd;
                    }
                    // create new children
                    else {
                        node["children"] = childrenToAdd;
                    }

                }

            }
        }
        ;


    });
}
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
            innerHTML: "<div class='binary-data' data-binary-position=" + pos + " data-parent-id=" + parentNodeId + ">Free "+ pos + " position</div>",
            is_new: true,
        };
        newNodes.push(newNode);
    }
    return newNodes;
};
var getVirtualFreeNode = function (parentNodeId, positions) {
    console.log('Add free node to:', parentNodeId, positions);

    var newNodes = createChildrenNodes(positions, parentNodeId);

    freeNodes.push({searchId: parentNodeId, children: newNodes});

}


var freeNodes = [];


var initBinaryTree = function (treeInstance) {

    analyzeTree(treeInstance.nodeStructure);

    /*var nodesToModify = _.map(freeNodes, function (obj) {
     return obj.searchId;
     });
     */
    modifyTree(treeInstance.nodeStructure);

    var treantInstance = new Treant(treeInstance);


    $("#tree-container").on("click",".node.free", function () {
        $dataElement =$(this).children(".binary-data").data();

        $("#binary-position").val($dataElement.binaryPosition);
        $("#binary-parent-id").val($dataElement.parentId);
        console.log($dataElement);
    })
}


