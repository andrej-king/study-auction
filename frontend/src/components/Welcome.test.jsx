import React from 'react';
import {render} from '@testing-library/react';
import "@testing-library/jest-dom/extend-expect";
import Welcome from "./Welcome";

test('renders welcome', () => {
  const {getByText} = render(<Welcome/>);
  const h1Element = getByText(/Auction/i);
  expect(h1Element).toBeInTheDocument();
});
