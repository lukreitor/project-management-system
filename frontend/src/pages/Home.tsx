import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Project } from '@/types';
import { projectService } from '@/services/projects';
import ProjectList from '@/components/projects/ProjectList';
import CreateProjectForm from '@/components/projects/CreateProjectForm';

export default function Home() {
  const navigate = useNavigate();
  const [projects, setProjects] = useState<Project[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchProjects = async () => {
    try {
      setIsLoading(true);
      setError(null);
      const data = await projectService.getAll();
      setProjects(data);
    } catch (err) {
      setError('Failed to load projects');
      console.error('Error fetching projects:', err);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchProjects();
  }, []);

  const handleCreateProject = async (data: { name: string }) => {
    await projectService.create(data);
    await fetchProjects();
  };

  const handleProjectClick = (project: Project) => {
    navigate(`/projects/${project.id}`);
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold">Projects</h1>
          <p className="text-muted-foreground mt-2">
            Manage your projects and tasks with weighted progress calculation
          </p>
        </div>
        <CreateProjectForm onSubmit={handleCreateProject} />
      </div>

      {isLoading && (
        <div className="text-center py-12">
          <p className="text-muted-foreground">Loading projects...</p>
        </div>
      )}

      {error && (
        <div className="text-center py-12">
          <p className="text-destructive">{error}</p>
        </div>
      )}

      {!isLoading && !error && (
        <ProjectList projects={projects} onProjectClick={handleProjectClick} />
      )}
    </div>
  );
}
