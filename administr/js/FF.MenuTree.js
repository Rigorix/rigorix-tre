// JavaScript Document
FF.MenuTree = {
	
	/* Configuration */
	imagePath: 'i/',
	cssClassesPrefix: 'menuTree_',
	elemWidth: Number(18),					// Larghezza degli elementi come cartelle e +
	initChildsDisplay: 'none',
	/* End configuration */
	
	init: function(menuID) {
		this.menuObj = $(menuID);
		this.setTree(this.menuObj, 0);
		Event.observe(window, 'resize', function() {
			$('MenuContainer_').setStyle({
				'height'	: document.viewport.getDimensions()['height'] - $$('.menuHeader.')[0].getDimensions()['height'],
				'overflow'	: 'auto',
				'margin'	: '0 6px 10px 0'
			});
		});
	},
	
	setTree: function(father, treeLevel) {
		father.className = this.cssClassesPrefix + 'treeHead';
		if(treeLevel != 0) father.style.paddingLeft = (this.elemWidth) + 'px';
		
		var childs = Element.childElements(father);
		if(childs.length > 0) {
			for(var i=0; i<childs.length; i++) {
				var obj = childs[i];
				var last = (this.isLastElement(obj)) ? '-end' : '';
				if(this.isFather(obj)) {
					obj.className = this.cssClassesPrefix + 'treeHead';
					if(!this.isLastElement(obj)) 
						obj.style.background = 'url(i/tree-branch.gif) repeat-y 0 0';
					this.setTree(obj.getElementsByTagName('ul')[0], treeLevel+1);
					var objContent = obj.innerHTML;
					obj.innerHTML = '<a href="#" onclick="FF.MenuTree.openTree(this.parentNode);"><img src="'+this.imagePath+'tree-node'+last+'.gif"></a>';
					obj.innerHTML += '<a href="#" onclick="FF.MenuTree.openTree(this.parentNode);"><img src="'+this.imagePath+'tree-folder.gif"></a>';
					if(objContent.indexOf('href="#"') != -1) 
						objContent = objContent.split('href="#"').join('href="#" onclick="FF.MenuTree.openTree(this.parentNode);"');
					obj.innerHTML += objContent;
				} else {
					console.log (jq(obj).attr ("name"))
					console.log (obj.innerHTML)
					var objContent = obj.innerHTML;
					var imgName = ( jq(obj).attr ("name") == "plugin" ) ? "tree-plugin.gif" : "tree-doc.gif";
						
					obj.innerHTML = '<img src="'+this.imagePath+'tree-leaf'+last+'.gif"><img src="'+this.imagePath+imgName+'">';
					obj.innerHTML += objContent;
				}
				if(!this.isFirstLevel(father)) 
					obj.style.display = FF.MenuTree.initChildsDisplay;
			}
		}
	},
	
	isFirstLevel: function(obj) {
		if(this.menuObj == obj) return true
		else return false;
	},
	
	isFather: function(obj) {
		if(obj.getElementsByTagName('ul').length > 0) return true;
		else return false;
	},
	
	isLastElement: function(obj) {
		if(Element.immediateDescendants(obj.parentNode)[Element.immediateDescendants(obj.parentNode).length-1] == obj) return true;
		return false; 
	},
	
	openTree: function(tree) {
		if(this.isFather(tree)) {
			var last = (this.isLastElement(tree)) ? '-end' : '';
			Element.down(tree,0).onclick = function(){FF.MenuTree.closeTree(this.parentNode)}
			Element.down(tree,1).src = this.imagePath + 'tree-node-open'+last+'.gif';
			Element.down(tree,2).onclick = function(){FF.MenuTree.closeTree(this.parentNode)}
			Element.down(tree,3).src = this.imagePath + 'tree-folder-open.gif';
			var node = tree.getElementsByTagName('UL')[0];
			var nodes = Element.immediateDescendants(node);
			for(var i=0; i<nodes.length; i++) {
				nodes[i].style.display = 'block';
			}
		}
	},
	
	closeTree: function(tree) {
		if(this.isFather(tree)) {
			var last = (this.isLastElement(tree)) ? '-end' : '';
			Element.down(tree,0).onclick = function(){FF.MenuTree.openTree(this.parentNode)}
			Element.down(tree,1).src = this.imagePath + 'tree-node'+last+'.gif';
			Element.down(tree,2).onclick = function(){FF.MenuTree.openTree(this.parentNode)}
			Element.down(tree,3).src = this.imagePath + 'tree-folder.gif';
			var node = tree.getElementsByTagName('UL')[0];
			var nodes = node.getElementsByTagName('li');
			for(var i=0; i<nodes.length; i++) {
				nodes[i].style.display = 'none';
			}
		}
	}
	
}