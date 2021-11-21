const { When } = require('@cucumber/cucumber')

// Before "When" will call: Given (support/steps/user.js), Then(support/steps/page.js)
When('I open {string} page', async function (uri) {
  return await this.page.goto('http://gateway:8080' + uri)
})
