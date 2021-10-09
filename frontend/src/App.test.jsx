import { render, screen } from '@testing-library/react';
import App from './App';

// test('renders learn react link', () => {
//   render(<App />);
//   const linkElement = screen.getByText(/learn react/i);
//   expect(linkElement).toBeInTheDocument();
// });

test('renders app', () => {
  const { getByText } = render(<App />);
  const h1Element = getByText(/Auction/i);
  expect(h1Element).toBeInTheDocument();
});
