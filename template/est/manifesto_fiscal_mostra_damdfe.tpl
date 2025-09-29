<div id="print">
{if $damdfe eq ''}
    <p>Manifesto n&atilde;o localizado!</p>
{else}
    <object data="{$damdfe}" style="width:100%; height:1000px;" type="application/pdf"></object>
{/if}
</div>
<style>
@keyframes tipsy {
0 {
transform: translateX(-50%) translateY(-50%) rotate(0deg);
}
100% {
transform: translateX(-50%) translateY(-50%) rotate(360deg);
}
}

body {
font-family: helvetica, arial, sans-serif;
background-color: #2e2e31;
}

p {
color: #fffbf1;
text-shadow: 0 20px 25px #2e2e31, 0 50px 60px #2e2e31;
font-size: 50px;
font-weight: bold;
text-decoration: none;
letter-spacing: -3px;
margin: 0;
position: absolute;
top: 50%;
left: 50%;
transform: translateX(-50%) translateY(-50%);
}

p:before,
p:after {
content: '';
padding: .9em .4em;
position: absolute;
left: 50%;
width: 115%;
top: 50%;
display: block;
border: 10px solid red;
transform: translateX(-50%) translateY(-50%) rotate(0deg);
animation: 10s infinite alternate ease-in-out tipsy;
}

p:before {
border-color: #d9524a #d9524a rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
z-index: -1;
}

p:after {
border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #d9524a #d9524a;
box-shadow: 25px 25px 25px rgba(46, 46, 49, .8);
}
</style>
    