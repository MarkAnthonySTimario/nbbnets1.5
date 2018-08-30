<template>
  <div class="row">
      <div class="col-lg-4 col-lg-offset-4 span-top"  id="login">
          <form action="login" method="post">
    		<h1>User Login</h1>
  			<div class="inset">
	  			<p>
	    		 <label for="userName">USER NAME</label>
   	 			<input type="text" placeholder="User Name" v-model="username" :readonly="loading" required autofocus/>
				</p>
  				<p>
				    <label for="password">PASSWORD</label>
				    <input type="password" placeholder="password" v-model="password" :readonly="loading" required/>
  				</p>
				  <p class="text-danger text-center" v-if="error && !loading">
				    {{error}}
				  </p>
 			 </div>

			  <p class="p-container">
			    <!-- <span><a href="#" v-if="!loading">Forgot password ?</a></span> -->
			    <span style="color:#fff;" v-if="loading">
					<loadingInline label=" Please wait.." class="pull-left"></loadingInline>
				</span>
			    <input type="submit" value="Login" @click.prevent="login" :disabled="loading">
                
			  </p>
		</form>
      </div>
  </div>
</template>

<script>

export default {
  data(){
      return {
          username : null, password : null, error : null, loading : false
      }
  },
 methods : {
	 login(){
		let {username, password} = this;
		  if(!this.username || !this.password){
              this.error = "Please provide username/password.";
              return;
          }
		  this.loading = true;

          axios.post("api/login",{
              username, password
          })
          .then(({data}) => {
			if(data.status){

				if(_.filter(this.$store.state.users,{user_id : username}).length == 0){
					Window.socket.emit("add-user",username);
				}
				
				Window.socket.connect();

				this.$session.set("token",data.token);
				this.$store.commit('TOKEN',data.token);
				
				this.$http.get(this,"keyvalues")
				.then(({data : {components,bloodtypes,civilstatus,bloodbags}}) => {
					if(data.user.facility.supports_platelet_apheresis){
						this.$session.set('components',components);
					}else{
						this.$session.set('components',_.filter(components,(c,i)=>i != '80'));
					}
					this.$session.set('blood_types',bloodtypes);
					this.$session.set('civil_status',civilstatus);
					this.$session.set('blood_bags',bloodbags);
					this.$session.set("user",data.user);
					this.$store.commit('USER',data.user);
				})
				.catch(error => {
					this.$store.state.error = error;
				});
            }else{
				this.password = '';
				this.error = data.error;
				this.loading = false;
			};
          });
      }
  }
}
</script>


<style>
.span-top{
    margin-top:50px;
}
/* reset */
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,dl,dt,dd,ol,nav ul,nav li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;}
article, aside, details, figcaption, figure,footer, header, hgroup, menu, nav, section {display: block;}
ol,ul{list-style:none;margin:0px;padding:0px;}
blockquote,q{quotes:none;}
blockquote:before,blockquote:after,q:before,q:after{background:'';background:none;}
table{border-collapse:collapse;border-spacing:0;}
/* start editing from here */
a{text-decoration:none;}
.txt-rt{text-align:right;}/* text align right */
.txt-lt{text-align:left;}/* text align left */
.txt-center{text-align:center;}/* text align center */
.float-rt{float:right;}/* float right */
.float-lt{float:left;}/* float left */
.clear{clear:both;}/* clear float */
.pos-relative{position:relative;}/* Position Relative */
.pos-absolute{position:absolute;}/* Position Absolute */
.vertical-base{	vertical-align:baseline;}/* vertical align baseline */
.vertical-top{	vertical-align:top;}/* vertical align top */
nav.vertical ul li{	display:block;}/* vertical menu */
nav.horizontal ul li{	display: inline-block;}/* horizontal menu */
img{max-width:100%;}
/*end reset*/
* { box-sizing: border-box; padding:0; margin: 0; }

body {
	font-family: 'Open Sans', sans-serif;
 	/* background:url(../images/bg.jpg) no-repeat center fixed; */
    -webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
 	background-size: cover;
 	background-size: 100% 100%;
}
.main{
	margin:13em auto 0;
	width: 22%;
}
#login form {
	background:#0A192A;
	border-radius:0.4em;
  	border:1px solid #0A192A;
  	overflow:hidden;
  	position:relative;
  	box-shadow: 0 5px 10px 5px rgba(0,0,0,0.2);
}

#login form:after {
  content:"";
  display:block;
  position:absolute;
  height:1px;
  width:100px;
  left:20%;
  top:0;
}

#login form:before {
 	content:"";
  display:block;
  position:absolute;
  width:8px;
  height:5px;
  border-radius:50%;
  left:34%;
  top:-7px;
  box-shadow: 0 0 6px 4px #fff;
}

.inset {
 padding:20px; 
  border-top:1px solid #000;
}

#login form h1{
  text-align:center;
  padding:18px 0;
  border-bottom:1px solid #000;
  position:relative;
}
#login form h1:after {
 content:"";
  display:block;
  width:250px;
  height:100px;
  position:absolute;
  top:0;
  left:50px;
  pointer-events:none;
 -webkit-transform: rotate(70deg);
 background: linear-gradient(50deg, rgba(255,255,255,0.15), rgba(0,0,0,0));
  
}

#login form h1  {
	color:#fff;
	text-shadow:0 1px 0 #000;
	font-size:18px;
	font-weight: 600;
	text-align:center;
  padding:18px 0;
  border-bottom:1px solid #000;
  position:relative;
	
}
#login form h1 span{
	padding:5px;
	background:#0184ff;
}
#login label {
 	color:rgba(137, 192, 243, 0.8);
  	display:block;
  	font-size:13px;
  	padding-bottom:9px;
}

#login input[type=text],
#login input[type=password] {
  font-family: 'Open Sans', sans-serif;
  width:100%;
  padding:8px 5px;
  background: rgb(167,167,167); /* Old browsers */
  background: -moz-linear-gradient(top,  rgba(167,167,167,1) 0%, rgba(181,181,181,1) 21%, rgba(228,228,228,1) 79%, rgba(242,242,242,1) 100%); /* FF3.6+ */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(167,167,167,1)), color-stop(21%,rgba(181,181,181,1)), color-stop(79%,rgba(228,228,228,1)), color-stop(100%,rgba(242,242,242,1))); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top,  rgba(167,167,167,1) 0%,rgba(181,181,181,1) 21%,rgba(228,228,228,1) 79%,rgba(242,242,242,1) 100%); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(top,  rgba(167,167,167,1) 0%,rgba(181,181,181,1) 21%,rgba(228,228,228,1) 79%,rgba(242,242,242,1) 100%); /* Opera 11.10+ */
  background: -ms-linear-gradient(top,  rgba(167,167,167,1) 0%,rgba(181,181,181,1) 21%,rgba(228,228,228,1) 79%,rgba(242,242,242,1) 100%); /* IE10+ */
  background: linear-gradient(to bottom,  rgba(167,167,167,1) 0%,rgba(181,181,181,1) 21%,rgba(228,228,228,1) 79%,rgba(242,242,242,1) 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a7a7a7', endColorstr='#f2f2f2',GradientType=0 ); /* IE6-9 */
  border:1px solid #222;
  box-shadow:0 1px 0 rgba(255,255,255,0.1);
  border-radius:0.3em;
  margin-bottom:20px;
  color: #000;
  font-size:15px;
  outline: none;
}
#login label[for=remember]{
 	color:#fff;
  	display:inline-block;
  	font-size: 13px;
}

#login input[type=checkbox] {
 	display:inline-block;
  	vertical-align:middle;
}

.p-container {
 	padding:0 20px 20px 20px; 
}

.p-container:after {
 	clear:both;
  display:table;
  content:"";
}

.p-container span a {
  font-size:14px;
  display:block;
  float:left;
  color:#0d93ff;
  padding-top: 4px;
}

#login input[type=submit] {
 	padding:5px 20px;
  border:1px solid #439F07;
  text-shadow:0 -1px 0 rgba(0,0,0,0.4);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 10px 10px rgba(255,255,255,0.1);
  border-radius:0.3em;
  background: rgb(138,199,84); /* Old browsers */
  background: -moz-linear-gradient(top,  rgba(138,199,84,1) 0%, rgba(121,201,50,1) 45%, rgba(102,190,24,1) 48%, rgba(57,150,2,1) 97%, rgba(102,174,63,1) 100%); /* FF3.6+ */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(138,199,84,1)), color-stop(45%,rgba(121,201,50,1)), color-stop(48%,rgba(102,190,24,1)), color-stop(97%,rgba(57,150,2,1)), color-stop(100%,rgba(102,174,63,1))); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top,  rgba(138,199,84,1) 0%,rgba(121,201,50,1) 45%,rgba(102,190,24,1) 48%,rgba(57,150,2,1) 97%,rgba(102,174,63,1) 100%); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(top,  rgba(138,199,84,1) 0%,rgba(121,201,50,1) 45%,rgba(102,190,24,1) 48%,rgba(57,150,2,1) 97%,rgba(102,174,63,1) 100%); /* Opera 11.10+ */
  background: -ms-linear-gradient(top,  rgba(138,199,84,1) 0%,rgba(121,201,50,1) 45%,rgba(102,190,24,1) 48%,rgba(57,150,2,1) 97%,rgba(102,174,63,1) 100%); /* IE10+ */
  background: linear-gradient(to bottom,  rgba(138,199,84,1) 0%,rgba(121,201,50,1) 45%,rgba(102,190,24,1) 48%,rgba(57,150,2,1) 97%,rgba(102,174,63,1) 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8ac754', endColorstr='#66ae3f',GradientType=0 ); /* IE6-9 */
 color:#fff;
  float:right;
  font-weight:bold;
  cursor:pointer;
  font-size:15px;
  outline: none;
}

#login input[type=submit]:hover {
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 -10px 10px rgba(255,255,255,0.1);
    background: rgb(102,174,63); /* Old browsers */
	background: -moz-linear-gradient(top,  rgba(102,174,63,1) 0%, rgba(57,150,2,1) 3%, rgba(102,190,24,1) 52%, rgba(121,201,50,1) 55%, rgba(138,199,84,1) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(102,174,63,1)), color-stop(3%,rgba(57,150,2,1)), color-stop(52%,rgba(102,190,24,1)), color-stop(55%,rgba(121,201,50,1)), color-stop(100%,rgba(138,199,84,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  rgba(102,174,63,1) 0%,rgba(57,150,2,1) 3%,rgba(102,190,24,1) 52%,rgba(121,201,50,1) 55%,rgba(138,199,84,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  rgba(102,174,63,1) 0%,rgba(57,150,2,1) 3%,rgba(102,190,24,1) 52%,rgba(121,201,50,1) 55%,rgba(138,199,84,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  rgba(102,174,63,1) 0%,rgba(57,150,2,1) 3%,rgba(102,190,24,1) 52%,rgba(121,201,50,1) 55%,rgba(138,199,84,1) 100%); /* IE10+ */
	background: linear-gradient(to bottom,  rgba(102,174,63,1) 0%,rgba(57,150,2,1) 3%,rgba(102,190,24,1) 52%,rgba(121,201,50,1) 55%,rgba(138,199,84,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#66ae3f', endColorstr='#8ac754',GradientType=0 ); /* IE6-9 */

}

/*----start-responsive design-----*/
@media only screen and (max-width:1680px) {
	.main{
	margin:15em auto 0;
	}
	.copy-right p {
	padding: 0em 0;
	}
}
@media only screen and (max-width:1440px)  {
	.main{
	margin:11em auto 0;
	width:24%;
	}
	.copy-right p {
	padding:3em 0;
	}
}
@media only screen and (max-width:1366px) {
	.main{
	margin:10em auto 0;
	width:25%;
	}
	.copy-right p {
	padding:3.5em 0;
	}
}
@media only screen and (max-width:1280px) {
	.main{
	margin:11em auto 0;
	width: 27%;
	}
	.copy-right p {
	padding:4.3em 0;
	}
}
@media only screen and (max-width:1024px)  {
	.main{
	margin:12em auto 0;
	width:33%;
	}
	.copy-right p {
	padding:6em 0;
	}
}
@media only screen and (max-width:768px) {
	.main{
	margin: 14em auto 0;
	width: 43%;
	}
	.copy-right p {
	padding:6em 0;
	}
}
@media only screen and (max-width:640px) {
	.main{
	margin:14em auto 0;
	width:51%;
	}
	.copy-right p {
	padding:6em 0;
	}
}
@media only screen and (max-width:480px)  {
	.main{
	margin: 12em auto 0;
	width: 68%;
	}
	.copy-right p {
	padding:6em 0;
	}
}
@media only screen and (max-width:320px) {
	.main{
	margin:7em auto 0;
	width:96%;
	}
	.copy-right p {
	padding:1em 0;
	}
}

/*----//end-responsive design-----*/
</style>
