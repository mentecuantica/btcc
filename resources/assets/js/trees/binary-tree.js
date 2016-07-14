import * as _ from "lodash";

class BinaryTreeOutput {

    constructor(nodes = '[]', parentNode, containerID="#tree-container") {
        this.nodes = JSON.parse(nodes);
        this.parentNode = parentNode;
        this.freeNodes = [];
        //this.parsedChildrenNodes = JSON.parse(childrenNodes);

        var treeInstanceConfig = this.generateTreeConfig(containerID);

        this.analyzeTree(treeInstanceConfig.nodeStructure);

        this.modifyTree(treeInstanceConfig.nodeStructure);

        var treantInstance = new Treant(treeInstanceConfig);


        jQuery(containerID).on("click", ".node.free", function (elem) {
            var dataElement = jQuery(this).children(".binary-data").data();

            jQuery("#binary-position").val(dataElement.binaryPosition);
            jQuery("#binary-parent-id").val(dataElement.parentId);
        })
    }


    generateTreeConfig(containerID) {
        var treeConfig = {
            chart: {
                container: containerID,
                node: {
                    collapsable: true
                },
            },
            nodeStructure: {
                HTMLclass: "owner",
                child_id: this.parentNode.user_id,
                collapsed: true,
                text: {
                    title: "You",
                    desc: this.parentNode.name,
                },

                children: this.nodes
            }
        };
        return treeConfig;
    }


    analyzeTree(treeObj) {
        // обходим все Node из списка в поисках Children
        var binaryPositions = ['L', 'R'];


        jQuery(treeObj).each((index, node)=> {

            // Children есть
            if (!node.hasOwnProperty('children')) {
                this.getVirtualFreeNode(node.user_id, binaryPositions)
                return;
            }

            length = node.children.length;
            if (0 === length) {
                this.getVirtualFreeNode(node.user_id, binaryPositions)
            }
            ;

            if (1 === length) {
                var existingPostion = node.bt_position;
                var singlePositionArray = existingPostion == 'R' ? ['L'] : ['R'];
                this.getVirtualFreeNode(node.user_id, singlePositionArray);
                //
                this.analyzeTree(node.children);
            }

            if (length === 2) {
                // iterate again
                this.analyzeTree(node.children);
            }
            ;

        });
    };


    modifyTree (nodes) {

        jQuery(nodes).each((index, node)=> {

            if (node.hasOwnProperty('children')) {
                console.log('Modify node has children: ', node);
                this.modifyTree(node.children);
            }

            if (!node.hasOwnProperty('user_id')) {
                return;
            }

            var childId = node.user_id;
            if (true == _.includes(_.map(this.freeNodes, obj=> {
                    return obj.searchId
                }), childId)) {
                console.log('We need add to node', childId);


                var newChildrenObj = _.find(this.freeNodes, obj => {
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
     * Creates virtual free node and pushes it to freeNodes array
     *
     * @param parentNodeId
     * @param positions
     */
    getVirtualFreeNode(parentNodeId, positions) {
        if (parentNodeId === undefined) {
            parentNodeId = window.Btcc.parent.id;
            console.log('Add free root:', parentNodeId, positions);
        }
        console.log('Add free node to:', parentNodeId, positions);

        var newNodes = this.createChildrenNodes(positions, parentNodeId);

        this.freeNodes.push({searchId: parentNodeId, children: newNodes});

    }


    /**
     * Add new objects for as children to parentNodeId
     *
     *
     * @param positions
     * @param parentNodeId
     * @returns {Array}
     */
     createChildrenNodes(positions, parentNodeId) {
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
                innerHTML: `<div class='binary-data' data-binary-position="${pos}" data-parent-id="${parentNodeId}">Free ${pos} position</div>`,
                is_new: true,
            };
            newNodes.push(newNode);
        }
        return newNodes;
    };
}

export default BinaryTreeOutput;