<div class="binary-tree-chart" id="tree-container"></div>


<script>
    var binary_tree_config = {
        chart: {
            container: "#tree-container"
        },

        nodeStructure: {
            text: {
                title: "You: " + "<?=$parent->name;?>",
                desc: "ID: " + "<?=$parent->id;?>",

            },
            children: {!! $jsonNodes !!}

        },


    };
    new Treant(binary_tree_config);
</script>