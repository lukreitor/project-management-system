import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Project } from '@/types';
import { projectService } from '@/services/projects';
import { taskService } from '@/services/tasks';
import { Button } from '@/components/ui/button';
import ProjectProgress from '@/components/projects/ProjectProgress';
import TaskList from '@/components/tasks/TaskList';
import CreateTaskForm from '@/components/tasks/CreateTaskForm';
import { LoadingState } from '@/components/ui/spinner';
import { Alert, AlertDescription, AlertIcon } from '@/components/ui/alert';
import { ArrowLeft } from 'lucide-react';

export default function ProjectDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [project, setProject] = useState<Project | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [operationError, setOperationError] = useState<string | null>(null);

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
    try {
      setOperationError(null);
      await taskService.create(data);
      await fetchProjectData();
    } catch (err) {
      setOperationError('Failed to create task. Please try again.');
      console.error('Error creating task:', err);
    }
  };

  const handleToggleTask = async (taskId: number) => {
    try {
      setOperationError(null);
      await taskService.toggle(taskId);
      await fetchProjectData();
    } catch (err) {
      setOperationError('Failed to update task. Please try again.');
      console.error('Error toggling task:', err);
    }
  };

  const handleDeleteTask = async (taskId: number) => {
    if (window.confirm('Are you sure you want to delete this task?')) {
      try {
        setOperationError(null);
        await taskService.delete(taskId);
        await fetchProjectData();
      } catch (err) {
        setOperationError('Failed to delete task. Please try again.');
        console.error('Error deleting task:', err);
      }
    }
  };

  if (isLoading) {
    return <LoadingState message="Loading project..." />;
  }

  if (error || !project) {
    return (
      <div className="space-y-4">
        <Alert variant="destructive">
          <AlertIcon variant="destructive" />
          <AlertDescription>{error || 'Project not found'}</AlertDescription>
        </Alert>
        <div className="flex justify-center">
          <Button onClick={() => navigate('/')}>
            Go Back
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6 animate-in">
      <div className="flex items-center gap-2 sm:gap-4">
        <Button variant="ghost" size="icon" onClick={() => navigate('/')}>
          <ArrowLeft className="h-5 w-5" />
        </Button>
        <div className="flex-1 min-w-0">
          <h1 className="text-xl sm:text-2xl md:text-3xl font-bold truncate">{project.name}</h1>
          <p className="text-muted-foreground mt-1 text-xs sm:text-sm">
            Created {new Date(project.created_at).toLocaleDateString()}
          </p>
        </div>
      </div>

      <div className="w-full sm:max-w-md">
        <ProjectProgress progress={project.progress} size="lg" />
      </div>

      <div className="border-t pt-6">
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
          <h2 className="text-xl sm:text-2xl font-semibold">Tasks</h2>
          <CreateTaskForm projectId={project.id} onSubmit={handleCreateTask} />
        </div>

        {operationError && (
          <Alert variant="destructive" className="mb-4">
            <AlertIcon variant="destructive" />
            <AlertDescription>{operationError}</AlertDescription>
          </Alert>
        )}

        <TaskList
          tasks={project.tasks || []}
          onToggle={handleToggleTask}
          onDelete={handleDeleteTask}
        />
      </div>
    </div>
  );
}
