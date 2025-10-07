@extends('layouts.site')

@section('title', 'Simulador de Ambiente — Studio Latitude')

@push('head')
<script src="https://unpkg.com/fabric@4.6.0/dist/fabric.min.js"></script>
<style>
    .sim-wrap {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 1rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .sim-panel {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        position: sticky;
        top: 6rem;
        height: fit-content;
        max-height: calc(100vh - 8rem);
        overflow-y: auto;
    }

    .sim-panel h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 1rem 0;
    }

    .sim-panel h3 {
        font-size: 0.75rem;
        font-weight: 600;
        margin: 1.25rem 0 0.5rem 0;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .sim-label {
        font-size: 0.875rem;
        color: #374151;
        display: block;
        margin: 0.75rem 0 0.375rem;
        font-weight: 500;
    }

    input[type="file"] {
        width: 100%;
        font-size: 0.875rem;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }

    .sim-row {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .sim-row input[type="range"] {
        flex: 1;
    }

    .sim-btn {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.625rem 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .sim-btn:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .sim-btn.primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .sim-btn.primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .sim-btn.warn {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    .sim-btn.warn:hover {
        background: #b91c1c;
        border-color: #b91c1c;
    }

    .sim-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .sim-canvasWrap {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        min-height: 600px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    #c {
        background: #ffffff;
    }

    .sim-pill {
        font-size: 0.75rem;
        color: #374151;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 0.375rem 0.625rem;
        display: inline-block;
        font-weight: 600;
        min-width: 50px;
        text-align: center;
    }

    .sim-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.5rem;
        line-height: 1.4;
    }

    .sim-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    @media (max-width: 980px) {
        .sim-wrap {
            grid-template-columns: 1fr;
            padding: 1rem;
        }

        .sim-panel {
            position: static;
            max-height: none;
            top: 0;
        }
    }
</style>
@endpush

@section('content')
<section class="bg-white py-8">
<div class="sim-wrap">
    <aside class="sim-panel">
        <h2>Simulador de Ladrilhos</h2>

        <h3>Imagens</h3>
        <label class="sim-label">1) Foto do ambiente (fundo)</label>
        <input id="bgInput" type="file" accept="image/*">
        <label class="sim-label">2) Textura do ladrilho (será repetida)</label>
        <input id="tileInput" type="file" accept="image/*">
        <div class="sim-hint">Se a textura já tem "junta" na foto, deixe <b>Espaçamento</b> = 0 px.</div>

        <h3>Definir área</h3>
        <div class="sim-grid">
            <button id="startPolyBtn" class="sim-btn">Desenhar área</button>
            <button id="finishPolyBtn" class="sim-btn" disabled>Finalizar</button>
            <button id="clearPolyBtn" class="sim-btn warn">Limpar área</button>
        </div>
        <div class="sim-hint sim-small">Clique para criar os vértices do polígono. Duplo-clique para encerrar.</div>

        <h3>Padrão</h3>
        <label class="sim-label">Escala</label>
        <div class="sim-row">
            <input id="scaleRange" type="range" min="10" max="300" value="120">
            <span id="scaleVal" class="sim-pill">120 px</span>
        </div>

        <label class="sim-label">Rotação</label>
        <div class="sim-row">
            <input id="angleRange" type="range" min="-90" max="90" value="0">
            <span id="angleVal" class="sim-pill">0°</span>
        </div>

        <label class="sim-label">Espaçamento (junta)</label>
        <div class="sim-row">
            <input id="gapRange" type="range" min="0" max="30" value="0">
            <span id="gapVal" class="sim-pill">0 px</span>
        </div>

        <label class="sim-label">Deslocamento X / Y</label>
        <div class="sim-row">
            <input id="offxRange" type="range" min="-300" max="300" value="0">
            <span id="offxVal" class="sim-pill">0</span>
        </div>
        <div class="sim-row">
            <input id="offyRange" type="range" min="-300" max="300" value="0">
            <span id="offyVal" class="sim-pill">0</span>
        </div>

        <label class="sim-label">Skew X (perspectiva horizontal)</label>
        <div class="sim-row">
            <input id="skewXRange" type="range" min="-30" max="30" value="0">
            <span id="skewXVal" class="sim-pill">0°</span>
        </div>

        <label class="sim-label">Skew Y (perspectiva vertical)</label>
        <div class="sim-row">
            <input id="skewYRange" type="range" min="-30" max="30" value="0">
            <span id="skewYVal" class="sim-pill">0°</span>
        </div>

        <h3>Exportar</h3>
        <div class="sim-grid">
            <button id="exportBtn" class="sim-btn primary">Baixar PNG</button>
            <button id="clearAllBtn" class="sim-btn">Limpar Tudo</button>
        </div>
    </aside>

    <main class="sim-canvasWrap">
        <canvas id="c" width="1200" height="800"></canvas>
    </main>
</div>
</section>
@endsection

@push('scripts')
<script>
        (function () {
            const canvas = new fabric.Canvas('c', {
                backgroundColor: '#ffffff',
                selection: false,
                preserveObjectStacking: true
            });

            let bgImg, tileImg;
            let drawingPoly = false;
            let tempPolyPoints = [];
            let polyObject = null;
            let patternRect = null;
            let pattern = null;

            const $ = id => document.getElementById(id);
            const readAsDataURL = f => new Promise(r => { const fr = new FileReader(); fr.onload = e => r(e.target.result); fr.readAsDataURL(f); });

            const bgInput = $('bgInput'), tileInput = $('tileInput');
            const scaleRange = $('scaleRange'), angleRange = $('angleRange'), gapRange = $('gapRange');
            const offxRange = $('offxRange'), offyRange = $('offyRange');
            const skewXRange = $('skewXRange'), skewYRange = $('skewYRange');

            const scaleVal = $('scaleVal'), angleVal = $('angleVal'), gapVal = $('gapVal');
            const offxVal = $('offxVal'), offyVal = $('offyVal');
            const skewXVal = $('skewXVal'), skewYVal = $('skewYVal');

            // ---- carregar imagens
            bgInput.onchange = async e => {
                if (!e.target.files[0]) return;
                const url = await readAsDataURL(e.target.files[0]);
                fabric.Image.fromURL(url, img => {
                    img.set({ evented: false, selectable: false, hoverCursor: 'default' });
                    fitToCanvas(img);
                    if (bgImg) canvas.remove(bgImg);
                    bgImg = img; canvas.add(bgImg); canvas.sendToBack(bgImg); canvas.requestRenderAll();
                }, { crossOrigin: 'anonymous' });
            };

            tileInput.onchange = async e => {
                if (!e.target.files[0]) return;
                const url = await readAsDataURL(e.target.files[0]);
                fabric.Image.fromURL(url, img => { tileImg = img; buildPattern(); }, { crossOrigin: 'anonymous' });
            };

            function fitToCanvas(img) {
                const cw = canvas.getWidth(), ch = canvas.getHeight();
                const s = Math.min(cw / img.width, ch / img.height);
                img.scale(s).set({ left: (cw - img.width * s) / 2, top: (ch - img.height * s) / 2 });
            }

            // ---- polígono
            $('startPolyBtn').onclick = () => {
                drawingPoly = true; tempPolyPoints = []; $('finishPolyBtn').disabled = false;
                canvas.getObjects('line').forEach(l => canvas.remove(l));
            };

            canvas.on('mouse:down', opt => {
                if (!drawingPoly) return;
                const p = canvas.getPointer(opt.e);
                tempPolyPoints.push(p);
                if (tempPolyPoints.length > 1) {
                    const a = tempPolyPoints[tempPolyPoints.length - 2], b = p;
                    canvas.add(new fabric.Line([a.x, a.y, b.x, b.y], { stroke: '#3b82f6', strokeWidth: 2, selectable: false, evented: false }));
                }
            });

            const finishPolygon = () => {
                if (!drawingPoly || tempPolyPoints.length < 3) return;
                drawingPoly = false;
                canvas.getObjects('line').forEach(l => canvas.remove(l));
                polyObject = new fabric.Polygon(tempPolyPoints, {
                    fill: 'rgba(0,0,0,0.08)', stroke: '#60a5fa', strokeWidth: 2,
                    objectCaching: false, selectable: true, hasBorders: true, hasControls: true
                });
                canvas.add(polyObject);
                $('finishPolyBtn').disabled = true;
                applyPatternToPolygon();
            };
            canvas.on('mouse:dblclick', finishPolygon);
            $('finishPolyBtn').onclick = finishPolygon;

            $('clearPolyBtn').onclick = () => {
                if (polyObject) { canvas.remove(polyObject); polyObject = null; }
                if (patternRect) { canvas.remove(patternRect); patternRect = null; }
                tempPolyPoints = []; drawingPoly = false;
                $('finishPolyBtn').disabled = true;
                canvas.requestRenderAll();
            };

            // ---- controles
            [scaleRange, angleRange, gapRange, offxRange, offyRange, skewXRange, skewYRange].forEach(inp => {
                inp.addEventListener('input', () => {
                    scaleVal.textContent = scaleRange.value + ' px';
                    angleVal.textContent = angleRange.value + '°';
                    gapVal.textContent = gapRange.value + ' px';
                    offxVal.textContent = offxRange.value;
                    offyVal.textContent = offyRange.value;
                    skewXVal.textContent = skewXRange.value + '°';
                    skewYVal.textContent = skewYRange.value + '°';
                    buildPattern();
                });
            });

            // ---- criar padrão (compatível fabric 4.x)
            function buildPattern() {
                if (!tileImg) {
                    if (patternRect) { patternRect.set('fill', null); canvas.requestRenderAll(); }
                    return;
                }
                const tileW = parseInt(scaleRange.value, 10);
                const tileH = Math.round(tileW * (tileImg.height / tileImg.width));
                const gap = parseInt(gapRange.value, 10);

                const pCan = document.createElement('canvas');
                pCan.width = tileW + gap;
                pCan.height = tileH + gap;
                const ctx = pCan.getContext('2d');
                ctx.imageSmoothingEnabled = true;
                ctx.clearRect(0, 0, pCan.width, pCan.height);
                ctx.drawImage(tileImg.getElement(), 0, 0, tileW, tileH);

                pattern = new fabric.Pattern({
                    source: pCan,
                    repeat: 'repeat',
                    offsetX: parseInt(offxRange.value, 10),
                    offsetY: parseInt(offyRange.value, 10),
                });

                // matriz = Rot(ang) * Skew(kx,ky)
                const toRad = d => d * Math.PI / 180;
                const ang = parseInt(angleRange.value, 10);
                const kx = Math.tan(toRad(parseInt(skewXRange.value, 10)));
                const ky = Math.tan(toRad(parseInt(skewYRange.value, 10)));
                const cos = Math.cos(toRad(ang)), sin = Math.sin(toRad(ang));
                const a = cos + (-sin) * ky;
                const b = sin + cos * ky;
                const c = cos * kx + (-sin);
                const d = sin * kx + cos;

                // fabric 4 usa patternTransform
                pattern.patternTransform = [a, b, c, d, 0, 0];

                // ligeiro atraso evita corrida com o clip
                setTimeout(applyPatternToPolygon, 0);
            }

            // ---- aplicar padrão recortado
            function applyPatternToPolygon() {
                if (!polyObject || !pattern) return;

                const rect = new fabric.Rect({
                    left: 0, top: 0, width: canvas.getWidth(), height: canvas.getHeight(),
                    selectable: false, evented: false, fill: pattern
                });

                    // clone can be asynchronous (especially when objects contain images)
                    // use the callback form to ensure we get a valid clipper object
                    polyObject.clone(function (clipper) {
                        clipper.set({ objectCaching: false, absolutePositioned: true });
                        rect.clipPath = clipper;

                        if (patternRect) canvas.remove(patternRect);
                        patternRect = rect;

                        canvas.add(patternRect);
                        if (bgImg) canvas.sendToBack(bgImg);

                        canvas.requestRenderAll();
                    });
            }

            canvas.on('object:modified', e => {
                if (e.target === polyObject && patternRect) { applyPatternToPolygon(); }
            });

            // ---- exportar PNG (sem a borda azul)
            $('exportBtn').onclick = () => {
                const prevActive = canvas.getActiveObject();
                const hadPoly = !!polyObject;
                let prevStroke, prevStrokeW;

                if (hadPoly) {
                    prevStroke = polyObject.stroke;
                    prevStrokeW = polyObject.strokeWidth;
                    polyObject.set({ strokeWidth: 0, stroke: 'rgba(0,0,0,0)' });
                }

                canvas.discardActiveObject();
                canvas.renderAll();

                const url = canvas.toDataURL({ format: 'png', multiplier: 1 });
                const a = document.createElement('a');
                a.href = url;
                a.download = 'simulacao-ladrilhos.png';
                a.click();

                if (hadPoly) {
                    polyObject.set({ strokeWidth: prevStrokeW, stroke: prevStroke });
                }
                if (prevActive) canvas.setActiveObject(prevActive);
                canvas.requestRenderAll();
            };

            // ---- limpar
            $('clearAllBtn').onclick = () => {
                [bgImg, patternRect, polyObject].forEach(o => { if (o) canvas.remove(o); });
                bgImg = tileImg = patternRect = polyObject = null; tempPolyPoints = [];
                ['bgInput', 'tileInput'].forEach(id => $(id).value = '');
                $('finishPolyBtn').disabled = true;
                canvas.requestRenderAll();
            };

            // ---- responsivo
            const resize = () => {
                const main = document.querySelector('.canvasWrap');
                const w = main.clientWidth - 20, h = window.innerHeight - 32;
                canvas.setWidth(Math.max(640, w));
                canvas.setHeight(Math.max(460, h - 32));
                canvas.requestRenderAll();
                if (bgImg) fitToCanvas(bgImg);
                if (patternRect && polyObject) applyPatternToPolygon();
            };
            window.addEventListener('resize', resize);
            resize();

            // atalhos
            window.addEventListener('keydown', e => {
                if (e.key === 'Escape' && drawingPoly) $('clearPolyBtn').click();
                if (e.key === 'Enter' && drawingPoly) finishPolygon();
        });
    })();
</script>
@endpush