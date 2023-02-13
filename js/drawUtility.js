// CANVAS DOM AND CONTEXT
let canvas;
let ctx;

function clearCanvas(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function fill(string){
    ctx.fillStyle = string;
}

function rect(x, y, width, height){
    ctx.rect(x, y, width, height);
    ctx.fill();
    ctx.stroke();
}

function line(x1, y1, x2, y2){
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();
}

function circle(x, y, radius){
    ctx.beginPath();
    ctx.arc(x, y, radius, 0, 2*Math.PI);
    ctx.fill();
    ctx.stroke();
}

function text(string, x, y){
    ctx.textAlign = "center";
    ctx.fillText(string, x, y);
    ctx.strokeText(string, x, y);
}