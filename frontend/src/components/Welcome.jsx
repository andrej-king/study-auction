import React from 'react';
import cl from './Welcome.module.css';

const Welcome = () => {
  return (
    <div className={cl.welcome}>
      <h1>Auction</h1>
      <p>We will be here soon</p>
    </div>
  );
};

export default Welcome;
