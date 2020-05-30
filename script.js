var btn = document.querySelector(".sai");
var getText = document.querySelector(".getText");
var content = document.querySelector(".getcontent");
// var editorContent = document.querySelector(".editor");

// btn.addEventListener("click", function() {
//   var s = editorContent.innerHTML;
//   content.style.display = "block";
//   content.textContent = s;
// });

// getText.addEventListener("click", function() {
//   const old = editorContent.textContent;
//   content.style.display = "block";
//   content.textContent = old;
// });

function link() {
  var url = prompt("Enter the URL");
  document.execCommand("createLink", false, url);
}

function copy() {
  document.execCommand("copy", false, "");
}

function changeColor() {
  var color = prompt("Enter your color in hex ex:#f1f233");
  document.execCommand("foreColor", false, color);
}


// function getImage() {
//   var file = document.querySelector("input[type=file]").files[0];

//   var reader = new FileReader();

//   let dataURI;

//   reader.addEventListener(
//     "load",
//     function() {
//       dataURI = reader.result;

//       const img = document.createElement("img");
//       img.src = dataURI;
//       editorContent.appendChild(img);
//     },
//     false
//   );

//   if (file) {
//     console.log("s");
//     reader.readAsDataURL(file);
//   }
// }

function printMe() {
  if (confirm("Check your Content before print")) {
    const body = document.body;
    let s = body.innerHTML;
    body.textContent = editorContent.innerHTML;

    document.execCommandShowHelp;
    body.style.whiteSpace = "pre";
    window.print();
    location.reload();
  }
}

var accordionItems = new Array();

function init() {
    // Grab the accordion items from the page
    var divs = document.getElementsByClassName(accordionItems);

    // Assign onclick events to the accordion item headings
    for (var i = 0; i < accordionItems.length; i++) {
        var h4 = getFirstChildWithTagName(accordionItems[i], 'H4');
        h4.onclick = toggleItem;
    }

    // Hide all accordion item bodies except the first
    for (var i = 1; i < accordionItems.length; i++) {
        accordionItems[i].className = 'accordionItem hide';
    }
}

function toggleItem() {
    var itemClass = this.parentNode.className;
    // Hide all items
    for (var i = 0; i < accordionItems.length; i++) {
        accordionItems[i].className = 'accordionItem hide';
    }
    // Show this item if it was previously hidden
    if (itemClass == 'accordionItem hide') {
        this.parentNode.className = 'accordionItem';
    }
}
function getFirstChildWithTagName( element, tagName ) {
    for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
    }
}
function sourceAccordionFill() {
    var sources = getElementsByClassName("accordionItems");
    for (var i = 0; i<sources.length; i++) {
        getElementsByClassName("accordionItems")[i].iframe.src = "https://github.com";//call to db ig? this is a placeholder
        getElementsByClassName("accordionItems")[i].h4 = "github"//set source heading to name of website...also placeholder
        
    }
}
    
//there are bugs in the two below functions; also the values probably need to be saved for future editing
function count_words(tbox_input) {
   no_words = tbox_input.value.split(" ");
   document.getElementById("wordDisplay").innerHTML = "Words left: " + (document.getElementsByName("wordLimit").innerHTML - no_words.length);
   return false;
}

function set_prompt_box() {
    window.alert(document.getElementsById("prompt").value);//debugging
    document.getElementById("promptDisplay").innerHTML = "Prompt: "+ document.getElementsById("prompt").innerHTML;
    return false;
}

/* CKEDITOR */

/**
 * Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

/* exported initSample */

if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'editor' );

		// :(((
		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
			);
		}

		// Depending on the wysiwygarea plugin availability initialize classic or inline editor.
		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'editor' );
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'editor' );

			// TODO we can consider displaying some info box that
			// without wysiwygarea the classic editor may not work.
		}
	};

	function isWysiwygareaAvailable() {
		// If in development mode, then the wysiwygarea must be available.
		// Split REV into two strings so builder does not replace it :D.
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

