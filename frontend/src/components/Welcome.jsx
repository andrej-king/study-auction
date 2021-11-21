import React from 'react'
import styles from './Welcome.module.css'

const Welcome = () => {
  return (
    <div data-testid="welcome" className={styles.welcome}>
      <h1>Auction</h1>
      <p>We will be here soon</p>
    </div>
  )
}

export default Welcome
