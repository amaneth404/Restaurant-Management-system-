<template>
<div>
 <b-row>
     <b-col md='12' class="container">
         <form action="">
                        <div class="row1">
                            <h2 style="text-align:center">Login with Social Media or Manually</h2>
                            <div class="vl">
                                <span class="vl-innertext">or</span>
                            </div>

                            <div class="col">
                                <a href="#" class="fb btn">
                                    <i class="fa fa-facebook fa-fw"></i> Login with Facebook
                                </a>
                                <a href="#" class="twitter btn">
                                    <i class="fa fa-twitter fa-fw"></i> Login with Twitter
                                </a>
                                <a href="#" class="google btn"><i class="fa fa-google fa-fw">
                              </i> Login with Google+
                            </a>
                            </div>

                            <div class="col">
                                <div class="hide-md-lg">
                                    <p>Or sign in manually:</p>
                                </div>

                                <input type="text" name="username" placeholder="Username" required>
                                <input type="password" name="password" placeholder="Password" required>
                                <input type="submit" value="Login">
                            </div>
                        </div>
                </form>
     </b-col>
     <b-col md='12'>
         
     </b-col>
 </b-row>
    </div>
</template>
<style scoped>
.container {
    position: relative;
    left:0;
    right:0;
    width:100%;
    border-radius: 5px;
    background-color: #413a5f;
    padding: 20px 0 30px 0;
    color: violet;
}


/* style inputs and link buttons */

input,
.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 4px;
    margin: 5px 0;
    opacity: 0.85;
    display: inline-block;
    font-size: 17px;
    line-height: 20px;
    text-decoration: none;
    /* remove underline from anchors */
}

input:hover,
.btn:hover {
    opacity: 1;
}


/* add appropriate colors to fb, twitter and google buttons */

.fb {
    background-color: rgb(0, 40, 87);
    color: white;
}

.twitter {
    background-color: #55ACEE;
    color: white;
}

.google {
    background-color: #dd4b39;
    color: white;
}


/* style the submit button */

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}


/* Two-column layout */

.col {
    float: left;
    width: 50%;
    margin: auto;
    padding: 0 50px;
    margin-top: 6px;
}


/* Clear floats after the columns */

.row1:after {
    content: "";
    display: table;
    clear: both;
}


/* vertical line */

.vl {
    position: absolute;
    left: 50%;
    transform: translate(-50%);
    border: 2px solid #ddd;
    height: 175px;
}


/* text inside the vertical line */

.vl-innertext {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 50%;
    padding: 8px 10px;
}


/* hide some text on medium and large screens */

.hide-md-lg {
    display: none;
}


/* bottom container */

.bottom-container {
    text-align: center;
    background-color: rgb(46, 114, 114);
    border-radius: 0px 0px 4px 4px;
}


/* -------foot---- */

.foot {
    text-align: center;
    background-color: rgba(51, 51, 51, 0.514);
}

</style>