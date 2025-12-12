import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Project, Task } from '@/types';
import { projectService } from '@/services/projects';
import { taskService } from '@/services/tasks';
import { Button } from '@/components/ui/button';
import ProjectProgress from '@/components/projects/ProjectProgress';
import TaskList from '@/components/tasks/TaskList';
import CreateTaskForm from '@/components/tasks/CreateTaskForm';
import { ArrowLeft } from 'lucide-react';

export default function ProjectDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [project, setProject] = useState<Project | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchProjectData = async () => {
    if (!id) return;

    try {
      setIsLoading(true);
      setError(null);
      const projectData = await projectService.getById(parseInt(id));
      setProject(projectData);
    } catch (err) {
      setError('Failed to load project');
      console.error('Error fetching project:', err);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchProjectData();
  }, [id]);

  const handleCreateTask = async (data: {
    title: string;
    difficulty: 'low' | 'medium' | 'high';
    project_id: number;
  }) => {
    await taskService.create(data);
    await fetchProjectData();
  };

  const handleToggleTask = async (taskId: number) => {
    await taskService.toggle(taskId);
    await fetchProjectData();
  };

  const handleDeleteTask = async (taskId: number) => {
    if (window.confirm('Are you sure you want to delete this task?')) {
      await taskService.delete(taskId);
      await fetchProjectData();
    }
  };

  if (isLoading) {
    return (
      <div className="text-center py-12">
        <p className="text-muted-foreground">Loading project...</p>
      </div>
    );
  }

  if (error || !project) {
    return (
      <div className="text-center py-12">
        <p className="text-destructive">{error || 'Project not found'}</p>
        <Button onClick={() => navigate('/')} className="mt-4">
          Go Back
        </Button>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4">
        <Button variant="ghost" size="icon" onClick={() => navigate('/')}>
          <ArrowLeft className="h-5 w-5" />
        </Button>
        <div className="flex-1">
          <h1 className="text-3xl font-bold">{project.name}</h1>
          <p className="text-muted-foreground mt-1">
            Created {new Date(project.created_at).toLocaleDateString()}
          </p>
        </div>
      </div>

      <div className="max-w-md">
        <ProjectProgress progress={project.progress} size="lg" />
      </div>

      <div className="border-t pt-6">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-2xl font-semibold">Tasks</h2>
          <CreateTaskForm projectId={project.id} onSubmit={handleCreateTask} />
        </div>

        <TaskList
          tasks={project.tasks || []}
          onToggle={handleToggleTask}
          onDelete={handleDeleteTask}
        />
      </div>
    </div>
  );
}
