const { setWorldConstructor } = require('@cucumber/cucumber')

function CustomWorld () {
  this.browser = null
}

setWorldConstructor(CustomWorld)
