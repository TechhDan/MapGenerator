class Game {

  constructor() {

    this.canvas = document.getElementById('canvas');
    this.context = this.canvas.getContext('2d');
    this.canvas.width = window.innerWidth;
    this.canvas.height = window.innerHeight;

    fetch('api.php')
      .then(response => response.json())
      .then(data => {
        this.drawMap(data);
        this.drawGrid(data);
      });
  }

  drawMap(data) {
    let color;
    for (let i = 0; i < data.map.length; i++) {
      for (let j = 0; j < data.map[i].length; j++) {
        color = data.map[i][j] ? 'green': 'blue';
        this.context.fillStyle = color;
        this.context.fillRect(i * data.tileSize, j * data.tileSize, data.tileSize, data.tileSize);
      }
    }
  }

  drawGrid(data) {
    for (let i = 0; i < data.map.length; i++) {
      for (let j = 0; j < data.map[i].length; j++) {
        this.context.beginPath();
        this.context.rect(i * data.tileSize, j * data.tileSize, data.tileSize, data.tileSize);
        this.context.stroke();
      }
    }
  }

}

window.onload = () => {
  window.Game = new Game();
};