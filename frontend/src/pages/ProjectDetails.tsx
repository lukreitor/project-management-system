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
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import { ArrowLeft, Trash2 } from 'lucide-react';

export default function ProjectDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [project, setProject] = useState<Project | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [operationError, setOperationError] = useState<string | null>(null);
  const [deleteTaskDialogOpen, setDeleteTaskDialogOpen] = useState(false);
  const [taskToDelete, setTaskToDelete] = useState<number | null>(null);
  const [deleteProjectDialogOpen, setDeleteProjectDialogOpen] = useState(false);

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
      const newTask = await taskService.create(data);

      // Update local state instead of refetching
      if (project) {
        const updatedProject = await projectService.getById(project.id);
        setProject(updatedProject);
      }
    } catch (err) {
      setOperationError('Failed to create task. Please try again.');
      console.error('Error creating task:', err);
    }
  };

  const handleToggleTask = async (taskId: number) => {
    if (!project) return;

    try {
      setOperationError(null);

      // Optimistic update
      const updatedTasks = project.tasks.map(task =>
        task.id === taskId ? { ...task, completed: !task.completed } : task
      );
      setProject({ ...project, tasks: updatedTasks });

      // Send request to server
      await taskService.toggle(taskId);

      // Fetch updated progress
      const updatedProject = await projectService.getById(project.id);
      setProject(updatedProject);
    } catch (err) {
      setOperationError('Failed to update task. Please try again.');
      console.error('Error toggling task:', err);
      // Revert on error
      await fetchProjectData();
    }
  };

  const handleDeleteTask = (taskId: number) => {
    setTaskToDelete(taskId);
    setDeleteTaskDialogOpen(true);
  };

  const handleDeleteProject = () => {
    setDeleteProjectDialogOpen(true);
  };

  const confirmDeleteTask = async () => {
    if (!project || !taskToDelete) return;

    try {
      setOperationError(null);

      // Optimistic update
      const updatedTasks = project.tasks.filter(task => task.id !== taskToDelete);
      setProject({ ...project, tasks: updatedTasks });

      await taskService.delete(taskToDelete);

      // Fetch updated progress
      const updatedProject = await projectService.getById(project.id);
      setProject(updatedProject);
    } catch (err) {
      setOperationError('Failed to delete task. Please try again.');
      console.error('Error deleting task:', err);
      // Revert on error
      await fetchProjectData();
    } finally {
      setDeleteTaskDialogOpen(false);
      setTaskToDelete(null);
    }
  };

  const confirmDeleteProject = async () => {
    if (!project) return;

    try {
      setOperationError(null);
      await projectService.delete(project.id);
      navigate('/');
    } catch (err) {
      setOperationError('Failed to delete project. Please try again.');
      console.error('Error deleting project:', err);
      setDeleteProjectDialogOpen(false);
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
        <Button
          variant="outline"
          size="icon"
          onClick={handleDeleteProject}
          className="text-destructive hover:text-destructive hover:bg-destructive/10"
        >
          <Trash2 className="h-5 w-5" />
        </Button>
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

      <ConfirmDialog
        open={deleteTaskDialogOpen}
        onOpenChange={setDeleteTaskDialogOpen}
        title="Delete Task"
        description="Are you sure you want to delete this task? This action cannot be undone."
        onConfirm={confirmDeleteTask}
        confirmText="Delete"
        cancelText="Cancel"
        variant="destructive"
      />

      <ConfirmDialog
        open={deleteProjectDialogOpen}
        onOpenChange={setDeleteProjectDialogOpen}
        title="Delete Project"
        description="Are you sure you want to delete this project? All tasks will be permanently removed. This action cannot be undone."
        onConfirm={confirmDeleteProject}
        confirmText="Delete Project"
        cancelText="Cancel"
        variant="destructive"
      />
    </div>
  );
}
