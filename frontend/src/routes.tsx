import { createBrowserRouter } from 'react-router-dom';
import Layout from './components/layout/Layout';
import Home from './pages/Home';
import ProjectDetails from './pages/ProjectDetails';

export const router = createBrowserRouter([
  {
    path: '/',
    element: (
      <Layout>
        <Home />
      </Layout>
    ),
  },
  {
    path: '/projects/:id',
    element: (
      <Layout>
        <ProjectDetails />
      </Layout>
    ),
  },
]);
