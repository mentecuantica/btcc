var binary_tree = {
    chart: {
        container: "#tree-container"
    },
    nodeStructure: {
        HTMLclass: "owner",
        text: {
            name: "You: " + "top@btcc.vgt",
            desc: "ID: " + "1",

        },
        children: [{
            "parent_id": 1,
            "child_id": 2,
            "bt_position": "L",
            HTMLclass: "partner",
            "level": 2,
            "name": "first@btcc.vgt",
            "id": 2,
            "text": {"n": "first@btcc.vgt", "title": "ID:2Level:2", "desc": "L"},
            "children": [{
                "parent_id": 2,
                "child_id": 4,
                HTMLclass: "partner",
                "bt_position": "L",
                "level": 3,
                "name": "fouth@bt.ru",
                "id": 4,
                "text": {"n": "fouth@bt.ru", "title": "ID:4Level:3", "desc": "L"},
            }]
        }, {
            "parent_id": 1,
            "child_id": 3,
            HTMLclass: "partner",
            "bt_position": "R",
            "level": 2,
            "name": "second@btcc.vgt",
            "id": 3,
            "text": {"n": "second@btcc.vgt", "title": "ID:3Level:2", "desc": "R"},
        }]

    },
};