// namespace (our namespace name) and undefined are passed here
// to ensure 1. namespace can be modified locally and isn't
// overwritten outside of our function context
// 2. the value of undefined is guaranteed as being truly
// undefined. This is to avoid issues with undefined being
// mutable pre-ES5.
;
;(function (NS, undefined) {

    NS.config = {'route': null};

    NS.analyzeTree = function (treeObj) {
        // обходим все Node из списка в поисках Children
        var binaryPositions = ['L', 'R'];

        jQuery(treeObj).each(function (index, node) {
            //var obj = nodeStructure;


            // Children есть
            if (node.hasOwnProperty('children')) {
                console.log('Index', index, 'treeObj', node);
                length = node.children.length;
                if (0 === length) {
                    // getVirtualFreeNode(node.child_id, binaryPositions);
                }
                ;

                if (1 === length) {
                    var existingPostion = node.bt_position;
                    var singlePositionArray = existingPostion == 'R' ? ['L'] : ['R'];

                    console.log({'Single': singlePositionArray});

                    getVirtualFreeNode(node.child_id, singlePositionArray);
                    //
                    NS.analyzeTree(node.children);
                }

                if (length === 2) {
                    // iterate again
                    NS.analyzeTree(node.children);
                }
                ;
            }
            else {
                // Children нет
                getVirtualFreeNode(node.child_id, binaryPositions)

            }
        });
    };


    NS.modifyTree = function (nodes) {
        jQuery(nodes).each(function (index, node) {
            if (node.hasOwnProperty('children')) {
                NS.modifyTree(node.children);
            }

            if (node.hasOwnProperty('child_id')) {
                var childId = node.child_id;
                if (true == _.contains(nodesToModify, childId)) {
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


    var freeNodes = [];

    function getVirtualFreeNode(parentNodeId, positions) {
        console.log('Add free node to:', parentNodeId, positions);
        var newNodes = createChildrenNodes(positions, parentNodeId);
        freeNodes.push({searchId: parentNodeId, children: newNodes});
    }
    var nodesToModify = _.map(freeNodes, function (obj)  { return obj.searchId; });

    function createChildrenNodes(positions, parentNodeId) {
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
                is_new: true,
            };
            newNodes.push(newNode);
        }
        return newNodes;
    };


    // private properties
    var foo = "foo",
        bar = "bar";

    // public methods and properties
    NS.config = [];
    NS.sayHello = function () {
        speak("hello world");
    };


    // private method
    function speak(msg) {
        console.log("You said: " + msg);
    };


    // check to evaluate whether 'namespace' exists in the
    // global namespace - if not, assign window.namespace an
    // object literal
})(window.btcc = window.btcc || {});


btcc.analyzeTree(binary_tree.nodeStructure);


btcc.modifyTree(binary_tree.nodeStructure);
