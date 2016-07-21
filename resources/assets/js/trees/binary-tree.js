import * as _ from "lodash";

class BinaryTreeOutput {

    constructor(nodes = '[]', parentNode, containerID = "#tree-container") {
        let treantInstance, treeInstanceConfig;
        this.nodes = JSON.parse(nodes);
        this.parentNode = parentNode;
        this.freeNodes = [];

        //this.parsedChildrenNodes = JSON.parse(childrenNodes);

        treeInstanceConfig = this.generateTreeConfig(containerID);

        this.analyzeTree(treeInstanceConfig.nodeStructure);

        let freeNodesClone = _.cloneDeep(this.freeNodes);

        console.log(this.freeNodes);

        this.modifyTree(treeInstanceConfig.nodeStructure);

        console.log(freeNodesClone);

        treantInstance = new Treant(treeInstanceConfig);


        jQuery(containerID).on("click", ".node.free", function () {
            let dataElement = jQuery(this).children(".binary-data").data();

            jQuery("#binary-position").val(dataElement.binaryPosition);
            jQuery("#binary-parent-id").val(dataElement.parentId);
        });
    }


    generateTreeConfig(containerID) {
        return {
            chart: {
                container: containerID,
                node: {
                    collapsable: true
                },
            },
            nodeStructure: {
                HTMLclass: "owner",
                child_id: this.parentNode.user_id,
                collapsed: false,
                text: {
                    title: "You",
                    desc: this.parentNode.name,
                },

                children: this.nodes
            }
        };
    }


    analyzeTree(treeObj) {
        // обходим все Node из списка в поисках Children
        const binaryPositions = ['L', 'R'];


        jQuery(treeObj).each((index, node)=> {

            let singlePositionArray, existingPostion, childrenLength;

            if (!node.hasOwnProperty('children')) {
                this.getVirtualFreeNode(node.user_id, binaryPositions);
                return;
            }

            childrenLength = node.children.length;

            if (0 === childrenLength) {
                this.getVirtualFreeNode(node.user_id, binaryPositions);
            }


            if (1 === childrenLength) {
                existingPostion = node.children[0].bt_position;  //
                singlePositionArray = existingPostion == 'R' ? ['L'] : ['R'];  // RUDE EXCLUSION, IF existing = L, then ['R']


                this.getVirtualFreeNode(node.user_id, singlePositionArray);
                this.analyzeTree(node.children);
            }

            if (childrenLength === 2) {
                // iterate again
                this.analyzeTree(node.children);
            }


        });
    }


    modifyTree(nodes) {

        jQuery(nodes).each((index, node)=> {

            let childrenToAdd, ifIncludes, newChildrenObj;

            if (node.hasOwnProperty('children')) {
                this.modifyTree(node.children);
            }

            if (!node.hasOwnProperty('user_id')) {
                return;
            }


            // VERY COMPLEX  // obj.searchID - who is the parent? check with node user_id
            ifIncludes = true == _.includes(_.map(this.freeNodes, obj=> {
                    return obj.searchId;
                }), node.user_id);

            if (ifIncludes) {
                newChildrenObj = _.find(this.freeNodes, obj => {
                    return obj.searchId == node.user_id;
                });

                if (undefined !== newChildrenObj) {
                    let newChildren = newChildrenObj.children;

                    if (newChildren.length == 2) {          // if children.lenght =2 , then inject to node and leave (2 IS MAX)
                        node.children = newChildren;  // if children.lenght =2 , then inject to node and leave (2 IS MAX)
                        return;
                    }

                    //if (node.hasOwnProperty('children')) { // at this moment we mix L or R
                    console.log('Modify by adding 1 this: ', newChildren, 'to this', node.children);

                    childrenToAdd = node.children.concat(newChildren);

                    if (childrenToAdd[0].bt_position == 'R') {
                        let tempR = childrenToAdd[0];
                        let tempL = childrenToAdd[1];
                        childrenToAdd = [tempL, tempR];
                    }

                    node.children = childrenToAdd;


                }
            }


        });
    }


    /**
     * Creates virtual free node and pushes it to freeNodes array
     *
     * @param parentNodeId
     * @param positions
     */
    getVirtualFreeNode(parentNodeId, positions) {
        let newNodes;
        if (parentNodeId === undefined) {
            parentNodeId = window.Btcc.parent.id;
            //console.log('Add free root:', parentNodeId, positions);
        }
        // console.log('Add free node to:', parentNodeId, positions);

        newNodes = BinaryTreeOutput.createChildrenNodes(positions, parentNodeId);

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
    static createChildrenNodes(positions, parentNodeId) {
        let i, newNode, pos, newNodes = [];

        for (i = 0; i < positions.length; i++) {
            pos = positions[i];
            newNode = {
                parent_id: parentNodeId,
                HTMLclass: `free free-${pos}`,
                bt_position: pos,
                innerHTML: `<div class='binary-data' data-binary-position="${pos}" data-parent-id="${parentNodeId}">${pos}</div>`,
                is_new: true,
            };
            newNodes.push(newNode);
        }
        return newNodes;
    }
}

export default BinaryTreeOutput;