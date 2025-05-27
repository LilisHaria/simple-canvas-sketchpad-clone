
class CanvasSketcher {
    constructor() {
        this.canvas = document.getElementById('drawing-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.isDrawing = false;
        this.isErasing = false;
        
        // Drawing properties
        this.currentColor = '#000000';
        this.currentSize = 5;
        this.currentOpacity = 1;
        
        // UI elements
        this.brushSizeSlider = document.getElementById('brush-size');
        this.brushSizeValue = document.getElementById('brush-size-value');
        this.colorPicker = document.getElementById('color-picker');
        this.opacitySlider = document.getElementById('opacity');
        this.opacityValue = document.getElementById('opacity-value');
        this.eraserBtn = document.getElementById('eraser-btn');
        this.clearBtn = document.getElementById('clear-btn');
        this.downloadBtn = document.getElementById('download-btn');
        this.cursor = document.getElementById('cursor');
        this.canvasDimensions = document.getElementById('canvas-dimensions');
        this.drawingMode = document.getElementById('drawing-mode');
        
        this.init();
    }

    init() {
        this.setupCanvas();
        this.bindEvents();
        this.updateCanvasInfo();
        this.updateDrawingMode();
    }

    setupCanvas() {
        // Set initial canvas size
        this.resizeCanvas();
        
        // Setup canvas context
        this.ctx.lineCap = 'round';
        this.ctx.lineJoin = 'round';
        
        // Handle window resize
        window.addEventListener('resize', () => this.resizeCanvas());
    }

    resizeCanvas() {
        const container = this.canvas.parentElement;
        const containerWidth = container.clientWidth;
        const aspectRatio = 4 / 3;
        
        // Calculate canvas dimensions
        const maxWidth = Math.min(containerWidth - 40, 1000);
        const maxHeight = window.innerHeight * 0.6;
        
        let canvasWidth = maxWidth;
        let canvasHeight = maxWidth / aspectRatio;
        
        if (canvasHeight > maxHeight) {
            canvasHeight = maxHeight;
            canvasWidth = canvasHeight * aspectRatio;
        }
        
        // Save current canvas content
        const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
        
        // Resize canvas
        this.canvas.width = canvasWidth;
        this.canvas.height = canvasHeight;
        
        // Restore canvas content (will be scaled)
        if (imageData.width > 0 && imageData.height > 0) {
            this.ctx.putImageData(imageData, 0, 0);
        }
        
        // Update canvas info
        this.updateCanvasInfo();
        
        // Reset drawing properties
        this.updateDrawingProperties();
    }

    updateCanvasInfo() {
        this.canvasDimensions.textContent = `${this.canvas.width} x ${this.canvas.height}`;
    }

    updateDrawingMode() {
        this.drawingMode.textContent = this.isErasing ? 'Eraser Mode' : 'Drawing Mode';
    }

    updateDrawingProperties() {
        this.ctx.globalCompositeOperation = this.isErasing ? 'destination-out' : 'source-over';
        this.ctx.strokeStyle = this.isErasing ? 'rgba(0,0,0,1)' : this.currentColor;
        this.ctx.lineWidth = this.currentSize;
        this.ctx.globalAlpha = this.isErasing ? 1 : this.currentOpacity;
    }

    bindEvents() {
        // Brush size
        this.brushSizeSlider.addEventListener('input', (e) => {
            this.currentSize = parseInt(e.target.value);
            this.brushSizeValue.textContent = this.currentSize;
            this.updateDrawingProperties();
            this.updateCursorSize();
        });

        // Color picker
        this.colorPicker.addEventListener('change', (e) => {
            this.currentColor = e.target.value;
            this.updateDrawingProperties();
        });

        // Opacity
        this.opacitySlider.addEventListener('input', (e) => {
            this.currentOpacity = parseFloat(e.target.value);
            this.opacityValue.textContent = Math.round(this.currentOpacity * 100) + '%';
            this.updateDrawingProperties();
        });

        // Eraser toggle
        this.eraserBtn.addEventListener('click', () => {
            this.isErasing = !this.isErasing;
            this.eraserBtn.classList.toggle('active', this.isErasing);
            this.updateDrawingProperties();
            this.updateDrawingMode();
        });

        // Clear canvas
        this.clearBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to clear the canvas?')) {
                this.clearCanvas();
            }
        });

        // Download
        this.downloadBtn.addEventListener('click', () => {
            this.downloadCanvas();
        });

        // Mouse events
        this.canvas.addEventListener('mousedown', (e) => this.startDrawing(e));
        this.canvas.addEventListener('mousemove', (e) => this.draw(e));
        this.canvas.addEventListener('mouseup', () => this.stopDrawing());
        this.canvas.addEventListener('mouseout', () => this.stopDrawing());

        // Touch events for mobile
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousedown', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            this.canvas.dispatchEvent(mouseEvent);
        });

        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousemove', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            this.canvas.dispatchEvent(mouseEvent);
        });

        this.canvas.addEventListener('touchend', (e) => {
            e.preventDefault();
            const mouseEvent = new MouseEvent('mouseup', {});
            this.canvas.dispatchEvent(mouseEvent);
        });

        // Cursor tracking
        this.canvas.addEventListener('mouseenter', () => {
            this.cursor.style.display = 'block';
        });

        this.canvas.addEventListener('mouseleave', () => {
            this.cursor.style.display = 'none';
        });

        this.canvas.addEventListener('mousemove', (e) => {
            this.updateCursor(e);
        });
    }

    getMousePos(e) {
        const rect = this.canvas.getBoundingClientRect();
        const scaleX = this.canvas.width / rect.width;
        const scaleY = this.canvas.height / rect.height;
        
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    startDrawing(e) {
        this.isDrawing = true;
        const pos = this.getMousePos(e);
        
        this.ctx.beginPath();
        this.ctx.moveTo(pos.x, pos.y);
    }

    draw(e) {
        if (!this.isDrawing) return;
        
        const pos = this.getMousePos(e);
        
        this.ctx.lineTo(pos.x, pos.y);
        this.ctx.stroke();
    }

    stopDrawing() {
        if (this.isDrawing) {
            this.isDrawing = false;
            this.ctx.beginPath();
        }
    }

    updateCursor(e) {
        const rect = this.canvas.getBoundingClientRect();
        this.cursor.style.left = (e.clientX - rect.left) + 'px';
        this.cursor.style.top = (e.clientY - rect.top) + 'px';
    }

    updateCursorSize() {
        const size = this.currentSize;
        this.cursor.style.width = size + 'px';
        this.cursor.style.height = size + 'px';
    }

    clearCanvas() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    downloadCanvas() {
        // Create a temporary canvas with white background
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        
        tempCanvas.width = this.canvas.width;
        tempCanvas.height = this.canvas.height;
        
        // Fill with white background
        tempCtx.fillStyle = 'white';
        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Draw the original canvas on top
        tempCtx.drawImage(this.canvas, 0, 0);
        
        // Download
        const link = document.createElement('a');
        link.download = `canvas-sketch-${Date.now()}.png`;
        link.href = tempCanvas.toDataURL();
        link.click();
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new CanvasSketcher();
});
