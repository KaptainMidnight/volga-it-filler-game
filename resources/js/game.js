const axios = require('axios')

const colors = axios.get('http://127.0.0.1:8000/api/colors').then(res => res.data)
let field = []
let tempField = []
let response = axios.get('http://127.0.0.1:8000/api/game').then(res => res.data)
const width = response.width
const height = response.height

let score = 0


// Random field
function fillField() {
    for (let i = 0; i < height; i++) {
        field[i] = []
        for (let j = 0; j < width; j++) {
            field[i][j] = Math.floor(Math.random() * colors.length)
        }
    }
}

// Show interface
function showInterface() {
    let fieldHtml = '<table><tr>\n'
    for (let i = 0; i < colors.length; i++) {
        fieldHtml += "<td bgcolor=\"" + colors[i] + "\" onclick=\"clicked(" + i + ");\"></td> \n"
    }
    fieldHtml += "</tr></table> \n"
    document.getElementById("game").innerHTML = fieldhtml;
}

// Show game field
function showField() {

}
