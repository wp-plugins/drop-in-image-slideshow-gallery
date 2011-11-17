// -------------------------------------------------------------------
// Plugin Name: drop in image slideshow gallery
// Description:  This 'drop in image slideshow gallery' is your regular image slideshow, 
// except each image is 'dropped' into view. this effect that works in all major browsers. 
// The slideshow stops dropping when the mouse is over it..
// DIISG: Drop in image slideshow gallery
// -------------------------------------------------------------------

var _DIISGcount=0

function DIISG(DIISG_imgarray, w, h, delay){
	this.id="_dropslide"+(++_DIISGcount) //Generate unique ID for this slideshow instance (automated)
	this.createcontainer(parseInt(w), parseInt(h))
	this.delay=delay
	this.DIISG_imgarray=DIISG_imgarray
	var preloadimages=[]
	for (var i=0; i<DIISG_imgarray.length; i++){
		preloadimages[i]=new Image()
		preloadimages[i].src=DIISG_imgarray[i][0]
	}
	this.animatestartpos=parseInt(h)*(-1) //Starting "top" position of an image before it drops in
	this.DIISG_slidedegree=10 //Slide degree (> is faster)
	this.DIISG_slidedelay=30 //Delay between slide animation (< is faster)
	this.DIISG_activecanvasindex=0 //Current "active" canvas- Two canvas DIVs in total
	this.DIISG_curimageindex=0
	this.zindex=100
	this.isMouseover=0
	this.init()
}


DIISG.prototype.createcontainer=function(w, h){
 document.write('<div id="'+this.id+'" style="position:relative; width:'+w+'px; height:'+h+'px; overflow:hidden">')
	document.write('<div style="position:absolute; width:'+w+'px; height:'+h+'px; top:0;"></div>')
	document.write('<div style="position:absolute; width:'+w+'px; height:'+h+'px; top:-'+h+'px;"></div>')
	document.write('</div>')
	this.DIISG_slideshowref=document.getElementById(this.id)
	this.DIISG_canvases=[]
	this.DIISG_canvases[0]=this.DIISG_slideshowref.childNodes[0]
	this.DIISG_canvases[1]=this.DIISG_slideshowref.childNodes[1]
}

DIISG.prototype.populatecanvas=function(DIISG_canvas, imageindex){
	var imageHTML='<img src="'+this.DIISG_imgarray[imageindex][0]+'" style="border: 0" />'
	if (this.DIISG_imgarray[imageindex][1]!="")
		imageHTML='<a href="'+this.DIISG_imgarray[imageindex][1]+'" target="'+this.DIISG_imgarray[imageindex][2]+'">'+imageHTML+'</a>'
	DIISG_canvas.innerHTML=imageHTML
}


DIISG.prototype.animateslide=function(){
	if (this.curimagepos<0){ //if image hasn't fully dropped in yet
		this.curimagepos=this.curimagepos+this.DIISG_slidedegree
		this.activecanvas.style.top=this.curimagepos+"px"
	}
	else{
		clearInterval(this.animatetimer)
		this.activecanvas.style.top=0
		this.setupnextslide()
		var slideshow=this
		setTimeout(function(){slideshow.rotateslide()}, this.delay)
	}
}


DIISG.prototype.setupnextslide=function(){
	this.DIISG_activecanvasindex=(this.DIISG_activecanvasindex==0)? 1 : 0
	this.activecanvas=this.DIISG_canvases[this.DIISG_activecanvasindex]
	this.activecanvas.style.top=this.animatestartpos+"px"
	this.curimagepos=this.animatestartpos
	this.activecanvas.style.zIndex=(++this.zindex)
	this.DIISG_curimageindex=(this.DIISG_curimageindex<this.DIISG_imgarray.length-1)? this.DIISG_curimageindex+1 : 0
	this.populatecanvas(this.activecanvas, this.DIISG_curimageindex)
}

DIISG.prototype.rotateslide=function(){
	var slideshow=this
	if (this.isMouseover)
		setTimeout(function(){slideshow.rotateslide()}, 50)
	else
		this.animatetimer=setInterval(function(){slideshow.animateslide()}, this.DIISG_slidedelay)
}

DIISG.prototype.init=function(){
	var slideshow=this
	this.populatecanvas(this.DIISG_canvases[this.DIISG_activecanvasindex], 0)
	this.setupnextslide()
	this.DIISG_slideshowref.onmouseover=function(){slideshow.isMouseover=1}
	this.DIISG_slideshowref.onmouseout=function(){slideshow.isMouseover=0}
	setTimeout(function(){slideshow.rotateslide()}, this.delay)
}

