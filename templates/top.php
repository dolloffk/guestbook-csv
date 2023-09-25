<!DOCTYPE html>
<html lang="en">
<head>
<title>Guestbook</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">

<style>
/* Default colors */
:root {
	--color:#212c2c;
	--background:#e8e3d6;
	
	--link:#804E00;
	--linkhover:#3c6d70;
	
	--formbg:#f4f1e9;
	--formcolor:#212c2c;
	
	--accentbg:#e0d9c6;
	--hr: #bb9860;
}

/* Alternate colors */
/* Replace "dark" with "light" and add your light mode colors here if you want your site to be in dark mode by default */
@media (prefers-color-scheme: dark) {
	:root {
		--color:#DCD7C9;
		--background:#2C3639;
		
		--link:#9bc4c7;
		--linkhover:#cca376;
		
		--formbg:#232c2f;
		--formcolor:#DCD7C9;
		
		--accentbg:#232c2f;
		--hr: #5b7e81;
	}
}

/* ------ The rest of the code starts here ------- */

* { margin: 0; padding: 0; transition:0.5s ease;}
img { margin:5px; max-width:100%; }

body { 
	color:var(--color);
	background:var(--background);
	font: 1.1rem sans-serif; 
}

main {
	padding:20px;
	width:90%;
	max-width:800px;
	margin:auto;
}

a {
	color:var(--link);
}

a:hover {
	color: var(--linkhover);
}

li a {
	text-decoration:none;
}

p {
	margin:10px 0px 10px 0px;
	line-height:1.5;
}

main h1 {
	font:2em Georgia, Times New Roman, serif;
	margin:5px 0 5px 0;
}

main h2 {
	margin:10px 0 10px 0;
	font:1.7em Georgia, Times New Roman, serif;
}

main h3 {
	margin:5px 0 5px 0;
	font: 1.4em Georgia, Times New Roman, serif;
}

main h4 {
	font:1em Georgia, Times New Roman, serif;
	font-style:italic;
	margin:5px 0 20px 0;
}

main input, textarea, select, button { 
	background: var(--formbg);  
	color: var(--formcolor);
	font: 12pt sans-serif; 
	border: 1px solid var(--hr); 
	padding: 5px; 
	margin: 5px;
	border-radius:5px;
}

main textarea {
	width:90%;
}

input[type="submit"], input[type="reset"] {
	font-size:13pt;
	background:var(--accentbg);
}

input[type="submit"]:hover, input[type="reset"]:hover {
	background:var(--hr);
	cursor:pointer;
}

main table {
	margin:10px auto 10px auto;
	padding:5px;
	width:100%;
	border-collapse:collapse;
}

main th {
	border-bottom:1px solid var(--hr);
	padding:5px;
	text-align:left;
}

main td {
	padding:3px;
}

main tr:nth-child(2n) {
	background:var(--accentbg);
}

main ul, ol { 
	list-style-position: outside;
	margin: 8px 0 8px 20px;
}

main li {
	margin:5px 0 5px 0;
	line-height:1.5;
}

main ul li ul, main ol li ol {
	margin-left:15px;
}

main blockquote {
	padding:10px;
	border-left:10px solid var(--hr);
	background:var(--accentbg);
}

main blockquote cite {
	font-size:11pt;
	position:relative;
	right:0;
}

main summary {
	cursor:pointer;
	margin: 5px 0px 5px 0px;
}

main details {
	padding:10px;
	background:var(--accentbg);
	margin:5px 0 5px 0;
    border-radius:5px;
}

main hr {
	border:0.5px solid var(--hr);
    margin:20px auto 20px auto;
}

main pre {
	padding:10px;
	background:var(--accentbg);
	margin:10px auto 10px auto;
	border-radius:5px;
	overflow:auto;
}

main footer {
	text-align:center;
	border-top: 0.5px solid var(--hr);
	margin-top:10px;
	padding:10px;
}

.q {
    padding:10px;
    background:var(--accentbg);
    border-radius:5px;
    width:90%;
}

.details {
    text-align:right;
    font-size:1.1rem;
}

.a {
    padding:10px;
    border:0.5px solid var(--hr);
    border-radius:5px;
    margin:10px 0 10px 40px;
    width:90%;
}

ul.pages {
    list-style:none;
    margin:auto;
    text-align:center;
}

ul.pages li { display:inline; }

ul.pages li a, ul.pages li.active {
    font-size: 1.2rem; 
    border: 1px solid var(--hr); 
    padding:5px;
    margin:2px;
    transition:0.5s ease;
    text-decoration:none;
}

ul.pages li.page a:hover {
    background:var(--formcolor);
    color:var(--formbg);
    transition:0.5s ease;
}

ul.pages li.page a {
    background: var(--formbg);  
    color: var(--formcolor);
}

ul.pages li.active {
    background:var(--formcolor);
    color:var(--formbg);
}


@media (prefers-color-scheme: dark) {
	main img {
		opacity:0.6;
	}
	
	main img:hover {
		opacity:1;
	}

	main a img:hover {
		opacity:1;
	}
}
</style>

</head>
<body>
<main>