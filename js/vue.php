<script>
  Vue.component('general-grid', {
    template: `
    <div class="general-grid">
      <div v-for="(row, i) in matrix1" class="row" :style="{ height: (100/numberOfRows)+'%' }">
        <button v-for="(col, j) in row" class="column" :style="{ width: (100/numberOfColumns)+'%' }" :class="(col ? 'active' : '')" @click="setValue(i, j)">{{col ? '*' : ''}}</button>
      </div>
    </div>
    `,
    props: ['numberOfRows', 'numberOfColumns'],
    data: function () {
      return {
        isMounted: false,
        matrix1: [],
        matrix2: [],
        setTimeoutID: 0
      }
    },
    mounted: function () {
      this.isMounted = true
      this.clear()
    },
    methods: {
      randomNumber(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1) + min);
      },
      copyMatrix(matrix) {
        let newMatrix = []
        matrix.forEach((rowElement) => {
          let row = []
          rowElement.forEach((columnElement) => {
            row.push(columnElement)
          });
          newMatrix.push(row)
        })
        return newMatrix
      },
      setValue(i, j) {
        Vue.set(this.matrix1[i], j, !this.matrix1[i][j])
      },
      updateMatrix() {
        this.matrix1.forEach((rowElement, i) => {
          rowElement.forEach((columnElement, j) => {
            let limit_min_i = i-1
            let limit_min_j = j-1
            let limit_max_i = i+1
            let limit_max_j = j+1
            if(i == 0) {
              limit_min_i = 0
            }
            if(j == 0) {
              limit_min_j = 0
            }
            if(i+1 >= rowElement.length) {
              limit_max_i = rowElement.length-1
            }
            if(j+1 >= columnElement.length) {
              limit_max_j = columnElement.length-1
            }
            let score = 0
            for(let k = limit_min_i ; k <= limit_max_i ; k++) {
              for(let l = limit_min_j ; l <= limit_max_j ; l++) {
                if(i == k && j == l) {
                  continue;
                }
                if(this.matrix1[k][l] == 1 ) {
                  score++
                }
              }
            }
            let nextValue = -1
            if(this.matrix1[i][j] == 1 && score <= 1) {
              nextValue = 0
            }
            else if(this.matrix1[i][j] == 1 && score <= 3) {
              nextValue = 1
            }
            else if(this.matrix1[i][j] == 0 && score == 3) {
              nextValue = 1
            }
            else if(this.matrix1[i][j] == 0 && score < 3 || score > 3) {
              nextValue = 0
            }
            else if(this.matrix1[i][j] == 1 && score > 3) {
              nextValue = 0
            }
            this.matrix2[i][j] = nextValue
          });
        })
        this.matrix1 = this.copyMatrix(this.matrix2)
      },
      renderFrame() {
        this.updateMatrix()
        this.setTimeoutID = setTimeout(() => {
          this.renderFrame()
        }, 300)
      },
      generateRandomMatrix() {
        this.matrix1 = []
        for (let i = 0; i < this.numberOfRows; i++) {
          let row = []
          for (let j = 0; j < this.numberOfColumns; j++) {
            row.push(this.randomNumber(0, 1))
          }
          this.matrix1.push(row)
        }
      },
      clear() {
        this.matrix1 = []
        for (let i = 0; i < this.numberOfRows; i++) {
          let row = []
          for (let j = 0; j < this.numberOfColumns; j++) {
            row.push(0)
          }
          this.matrix1.push(row)
        }
        this.matrix2 = this.copyMatrix(this.matrix1)
      },
      play() {
        this.renderFrame()
      },
      pause() {
        clearTimeout(this.setTimeoutID);
      },
      stop() {
        this.pause()
        this.clear()
      }
    }
  })
  
  var app = new Vue({
    el: '#app',
    methods: {
      play() {
        this.$refs.generalGrid.play()
      },
      pause() {
        this.$refs.generalGrid.pause()
      },
      stop() {
        this.$refs.generalGrid.stop()
      },
      clear() {
        this.$refs.generalGrid.clear()
      },
      random() {
        this.$refs.generalGrid.generateRandomMatrix()
      }
    }
  })
</script>